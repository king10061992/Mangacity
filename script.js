////////////////////////////////////////////////////
//////CURVITRON  PAR ANDRE COLOT ///////////////////
////////////////////////////////////////////////////

///VARIABLE/////////////////////////////////////////

//Definis les standard ou cree le jeu//

	var canvas = document.getElementById("mygame");
	var ball = canvas.getContext("2d");
	var drawnBalls = [];
	var drawnBallsBuffer = [];
	var boxbonus = canvas.getContext("2d");

// position de depart de la ball//

	var x = Math.random()*canvas.width;
	var y = Math.random()*canvas.height;

// position de depart de la ball//

	var boxx = Math.random()*canvas.width;
	var boxy = Math.random()*canvas.height;

//deplacement //

	var dx=0;
	var dy=-1;

//Touche//

	var rightPressed = false;
	var leftPressed = false;

//Defini les valeur par defaut//

	var speed=10; //vitesse
	var strong=7; //taille
	var point=0;  //! a ne pas modifier

// Defini la taille//

	var spaceWidth= 0;
	var spaceHeight= 0;

//reprend l information reset //

	var resetButton= "<div class='resetbutton'><p>Play Again</p></div>";

//Cree le score//
        $.ajax({
           url : './js/script.php',
           type : 'GET',
           data : 'score=all',
           dataType: 'json',
           
           success : function(data){ // success est toujours en place, bien sûr !
               console.log("Requête Ajax GET reçue");
               console.log(data);
               //var score = JSON.parse(data);
               score= data;
               highScore1= parseFloat(score[0].score);
               highScore2= parseFloat(score[1].score);
               highScore3= parseFloat(score[2].score);
               highScore4=parseFloat( score[3].score);
               highScore5= parseFloat(score[4].score);
           },
           
           error:function(){
               console.log("Erreur dans le code");
           }
       });
       
	var i = 0;
	var starttimer= "false";

//music
	var player = document.querySelector('#audioPlayer');

//////////////////////////////////////////////////////
////CREATION DES OBJETS///////////////////////////////
//////////////////////////////////////////////////////
//dessine le player///////////////////

	function draw() {

            //la boule est cree
            ball.beginPath();
	    ball.arc(x, y, strong, point, Math.PI*2);
	    ball.fillStyle = "#ffffff";
	    ball.fill();
	    ball.closePath();

	    //la boule les deplacement
	    x += Math.sin(dx* Math.PI / 180);
	    y += Math.cos(dx * Math.PI / 180);

	    //collision principal////////////////////
	    detectCollision();

	    //creation des variable
	    var matrixX = Math.floor(x/strong);
	    var matrixY = Math.floor(y/strong);

	    drawnBallsBuffer.push({x:matrixX,y:matrixY});

	    //deplacement de la balle
        if (drawnBallsBuffer.length > 30){

			var firstElement = drawnBallsBuffer.splice(0,1)[0]; // cree un tableau avec les ancienne coordonnee

            if (typeof drawnBalls[firstElement.x] == 'undefined'){

                drawnBalls[firstElement.x] = [];

            }
            drawnBalls[firstElement.x][firstElement.y] =1; // reset les position
        }

        if (typeof drawnBalls[matrixX] != 'undefined' && typeof drawnBalls[matrixX][matrixY] !== "undefined"){

        	stop(0);

		}
    }

//////////////////////////////////////////////////////
///// INITALISATION //////////////////////////////////
//////////////////////////////////////////////////////

//Inialise le code //
$(document).ready(function(){
	init();
});

// donne les condition pour cree le jeu
	function playGame(){
		starttimer= "true";
		timer();
		player.play();
	}

// donne les instruction de depart
function init(){

	//defini l espace de jeu
	var spaceWidth= $(document).width();
	var spaceHeight= $(document).height();

	/* Demmare le jeu*/
	$(".startbutton").on('click',function(){
		playGame();

		});

	/* suspendre le jeu*/

	$(".stopbutton").click(function(){

		clearInterval(clock);

			if(starttimer== "true"){
				starttimer= "false";
				player.pause();
				updateScores(i);
				darken(".space");
			};
		});

	/*Affiche le highscores depuis le bas */

	$("#highscores").slideUp(1);

	$(".space").css({

		"width":spaceWidth,
		"height":spaceHeight
	});
}

//////////////////////////////////////////////////////
//// TIMER ///////////////////////////////////////////
//////////////////////////////////////////////////////

/* Fonction pour que le timer demmarre*/

function timer(){

	if(starttimer=="true"){
		clock = setInterval(function(){
			i=i+(0.001*speed);
			$(".timer").html("<p>"+ i.toFixed(2) + "</p>");
			draw();
		},speed);
	};
};

//////////////////////////////////////////////////////
//// SCOREBOARD/// ///////////////////////////////////
//////////////////////////////////////////////////////

/* Masque les element*/

function darken(element){
		$(element).css("opacity", "0.1");
};

/* gere le nouveau score */
function updateScores(newScore){

	if(newScore > highScore1){
		var redScore="score1";
		highScore5=highScore4;
		highScore4=highScore3;
		highScore3=highScore2;
		highScore2=highScore1;
		highScore1=newScore;

	}else if(newScore > highScore2){

		var redScore="score2";
		highScore5=highScore4;
		highScore4=highScore3;
		highScore3=highScore2;
		highScore2=newScore;

	}else if(newScore > highScore3){

		var redScore="score3";
		highScore5=highScore4;
		highScore4=highScore3;
		highScore3=newScore;

	}else if(newScore > highScore4){

		var redScore="score4";
		highScore5=highScore4;
		highScore4=newScore;

	}else if(newScore > highScore5){

		var redScore="score5";
		highScore5=newScore;
	};

// Cree le html 
	var highScorePlace1= "<div class='score' id='score1'><h3>" + " 1er:"+ highScore1.toFixed(2) + "</h3></div>";
	var highScorePlace2= "<div class='score' id='score2'><h3>" + " 2eme:" + highScore2.toFixed(2) + "</h3></div>";
	var highScorePlace3= "<div class='score' id='score3'><h3>" + " 3eme:"+ highScore3.toFixed(2) + "</h3></div>";
	var highScorePlace4= "<div class='score' id='score4'><h3>" + " 4eme:"+ highScore4.toFixed(2) + "</h3></div>";
	var highScorePlace5= "<div class='score' id='score5'><h3>" + " 5eme:"+ highScore5.toFixed(2) + "</h3></div>";
	var formulaire= $("<from method='post'><label> Votre Score </label><input type='text' name='score' value="+newScore.toFixed(2)+" id='userScore' readonly/><br/><label>Pseudo</label><input type='text' name='pseudo' maxlength='3' id='pseudo'/><br/></form>");

	formulaire.on('submit',postf);

//dit ou il est placer
	$("#highscores").append(highScorePlace1, highScorePlace2, highScorePlace3, highScorePlace4, highScorePlace5, formulaire,resetButton);

// condition d affichage(css)
	$("#highscores").slideDown(1000);
	$("#"+redScore).css("color", "darkgrey");
	$(".resetbutton").click(function(){
            formulaire.trigger('submit');
            gameReset();

	});
};

/* envoie le formulaire*/
function postf(e){
    if (typeof e != 'undefined'){
        e.preventDefault();
    }
    
/*
	var id=Math.random().toFixed(2)*100;
	alert(id);
*/
	var pseudime=document.getElementById('pseudo').value;
	//alert(pseudime);
        console.log(pseudime);

	var uscore=document.getElementById('userScore').value;
	console.log(uscore);

	$.ajax({
            type: 'POST',
            url: './js/script.php',
            data: {pseudo: pseudime ,score:uscore},
            cache: false,

        success: function(){
            console.log("Merci d avoir jouer, les donnees ont ete transmise");
        },
        
        error: function(){
            console.log("OMG y a un bug dans le code");
        }
    });
}

/* rest le jeu*/
function gameReset(){

	$("#highscores").slideUp(1000, function(){

		postf();
		$(".space").css("opacity", "1.0");
		i = 0;
		$(".timer").html("<p>"+ i.toFixed(2) + "</p>");
		$(".resetbutton").remove();
		$(".score").remove();
		location.reload();

	});
};

//////////////////////////////////////////////////////
//// LES CONTROLES ///////////////////////////////////
//////////////////////////////////////////////////////

// section touche//

//defini les touche comme off
document.addEventListener("keydown", keyDownHandler, false);
document.addEventListener("keyup", keyUpHandler, false);

//defini ce qu il font si activer

	function keyDownHandler(e) {
		console.log("Key pressed "+e.keyCode);
            if(e.keyCode == 39) {
	        rightPressed = true;
	        dx-=5;
	    }

	    else if(e.keyCode == 37) {
	        leftPressed = true;
	        dx+= 5;
	    }
	}

	function keyUpHandler(e){

	    if(e.keyCode == 39){
	        rightPressed = false;
	    }
	    else if(e.keyCode == 37){
	        leftPressed = false;
	    }
	}

//////////////////////////////////////////////////////
//// HITBOX //////////////////////////////////////////
//////////////////////////////////////////////////////

function detectCollision(){

	var i = 0;

	if (typeof drawnBalls[x] != "undefined" && typeof drawnBalls[x][y] != "undefined"){
        stop(0);
	}

//Condition trace//
//recupere les cordonner et le place dans un tableau
	var myArray={

		x: [x],
    	y: [y]
	};

	while(x==myArray||y==myArray) {

		myArray.push(x);
		myArray.push(y);

		console.log(myArray);
		stop(0);
	};

//Condition secondaire//
// stop si la bordure et le player se touche
	if (x<0 || x>canvas.width - strong*2 || y<0 || y>canvas.height-strong*2){

		console.log(x);
		console.log(canvas.width);
		console.log(canvas.width - strong*2);
		console.log(y);
		console.log(canvas.height);
		stop(0);
	}
}

// Option d arret
function stop(distance){

	if (distance < strong + strong) {

		//detecte colison
		console.log("COLLISION")

		//stop le temps
		clearInterval(clock);

		//arrete le jeu et fini le jeu
		 if(starttimer== "true"){
			starttimer= "false";
			player.pause();

			//affiche le score
			updateScores(i);
			darken(".space");
		};
	}
	i++;
}