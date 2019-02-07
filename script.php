<?php
header( 'content-type: text/html; charset=utf-8' );
            function newscore ($bdd, $pseudo, $score){
                try{

                    //insert les different element dans le tableau
                    $req = "INSERT INTO `curvytron` ( `pseudo`,`score`) VALUES ( :pseudonime,:uscore)";

                    //prepare et lance la requete
                    $query = $bdd->prepare($req);
                    $result=$query->execute([
                            'pseudonime'=>$pseudo,
                            'uscore'=>$score
                            ]);

                //message pour recuper les elements
                } catch (PDOException $ex) {
                    die($ex->getMessage());
                }
                return $result;
            }
            
//------------------------------------------------------------------------------
            
 function getScore($bdd){

        try{

            $query = $bdd->query('SELECT `score` FROM `curvytron` ORDER BY `score` DESC LIMIT 5');
            $result = $query->fetchAll();

            echo json_encode($result);
            //message de récupération des éléments

            } 
            catch (PDOException $ex) {
                die($ex->getMessage());
            }
        }

//------------------------------------------------------------------------------
        
        try{
            /* Introduit les valeur de connection */
            $bdd = new PDO('mysql:host=localhost;dbname=colot;charset=utf8', 'colot', '3GoRRV2wVF86Rtw');
            $bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }

        /* renvoie une erreur si mauvais log dans la base de donner */
        catch(Exception $e){
            /* note le message d erreur */
            die('Erreur : '.$e->getMessage());
        }
        
        if (isset($_GET['score'])){
          getScore($bdd);
        } else if (isset($_POST['score'])) {
            newscore($bdd,$_POST['pseudo'],$_POST['score']); 
        }else {
            return $bdd; /* retourne a la variable bdd */
        }
    
       
        
        