<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');

try {
    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $bdd = new PDO('mysql:host=localhost;dbname=test', 'root', '', $pdo_options);
    //----
    $json = file_get_contents('php://input');
    $obj = json_decode($json, TRUE);
    //RECEIVED DATA

    $chaineDateUTC = gmdate('Y') . '-' . gmdate('m') . '-' . gmdate('d') . ' ' . gmdate('H') . ':' . gmdate('i') . ':' . gmdate('s');

    //1. PROCESS DATA FROM DEVICE TO SERVER
    if (isset($_POST['jobTODO']) && $_POST['jobTODO'] == 'transfertThisDataFromDeviceToServer') {
        $textReceived = $_POST['text'];
        $audioReceived = 'left-blank';
        $videoReceived = 'left-blank';
        $imageReceived = 'left-blank';
        $imeiNumber = $_POST['imei'];
        //Generate a temporary fingerprint
        $uniqueString = $imeiNumber . '+' . $chaineDateUTC;
        $fingerprint = hash_hmac('ripemd320', $uniqueString, 'ENCODED-SERVER-CENTRAL');
        //Array images just uploaded filenames

        if (isset($_FILES['image']) || isset($_FILES['video']) || isset($_FILES['audio']))     //Media uploaded
        {
            if (isset($_FILES['image'])) {
                $imageReceived = $_FILES['image'];
                $arrayImagesPathUploaded = [];
                //Upload the images
                for ($i = 0; $i < count($imageReceived['name']); $i++) {
                    $tmpImageFilename = $imageReceived['name'][$i];
                    //echo $tmpImageFilename;
                    $infosFile = pathinfo($tmpImageFilename);
                    $extensionFile = $infosFile['extension'];
                    $tmpImageFilename = explode('.', $tmpImageFilename)[0] . '-' . $fingerprint . '.' . $extensionFile;
                    //Upload
                    $chefDeBoucle = true;
                    $loopChecker = 0;
                    $wasImageUploaded = false;
                    do {
                        if (move_uploaded_file($imageReceived['tmp_name'][$i], 'assets/App/images/' . $tmpImageFilename)) {
                            $chefDeBoucle = false;
                            $wasImageUploaded = true;
                            //Save image uploaded
                            if (!in_array('assets/App/images/' . $tmpImageFilename, $arrayImagesPathUploaded)) {
                                array_push($arrayImagesPathUploaded, 'assets/App/images/' . $tmpImageFilename);
                            }
                        }
                        $loopChecker += 1;
                        if ($loopChecker >= 100)
                            $chefDeBoucle = false;
                    } while ($chefDeBoucle);
                }
            }

            if (isset($_FILES['video'])) {
                $videoReceived = $_FILES['video'];
                $arrayVideosPathUploaded = [];
                //Upload the images
                for ($i = 0; $i < count($videoReceived['name']); $i++) {
                    $tmpVideoFilename = $videoReceived['name'][$i];
                    //echo $tmpImageFilename;
                    $infosFile = pathinfo($tmpVideoFilename);
                    $extensionFile = $infosFile['extension'];
                    $tmpVideoFilename = explode('.', $tmpVideoFilename)[0] . '-' . $fingerprint . '.' . $extensionFile;
                    //Upload
                    $chefDeBoucle = true;
                    $loopChecker = 0;
                    $wasVideoUploaded = false;
                    do {
                        if (move_uploaded_file($videoReceived['tmp_name'][$i], 'assets/App/videos/' . $tmpVideoFilename)) {
                            $chefDeBoucle = false;
                            $wasImageUploaded = true;
                            //Save video uploaded
                            if (!in_array('assets/App/videos/' . $tmpVideoFilename, $arrayVideosPathUploaded)) {
                                array_push($arrayVideosPathUploaded, 'assets/App/videos/' . $tmpVideoFilename);
                            }
                        }
                        $loopChecker += 1;
                        if ($loopChecker >= 100)
                            $chefDeBoucle = false;
                    } while ($chefDeBoucle);
                }
            }

            if (isset($_FILES['audio'])) {
                $audioReceived = $_FILES['audio'];
                $arrayAudiosPathUploaded = [];
                //Upload the images
                for ($i = 0; $i < count($audioReceived['name']); $i++) {
                    $tmpAudioFilename = $audioReceived['name'][$i];
                    //echo $tmpImageFilename;
                    $infosFile = pathinfo($tmpAudioFilename);
                    $extensionFile = $infosFile['extension'];
                    $tmpAudioFilename = explode('.', $tmpAudioFilename)[0] . '-' . $fingerprint . '.' . $extensionFile;
                    //Upload
                    $chefDeBoucle = true;
                    $loopChecker = 0;
                    $wasVideoUploaded = false;
                    do {
                        if (move_uploaded_file($audioReceived['tmp_name'][$i], 'assets/App/audios/' . $tmpAudioFilename)) {
                            $chefDeBoucle = false;
                            $wasAudioUploaded = true;
                            //Save audio uploaded
                            if (!in_array('assets/App/audios/' . $tmpAudioFilename, $arrayAudiosPathUploaded)) {
                                array_push($arrayAudiosPathUploaded, 'assets/App/audios/' . $tmpAudioFilename);
                            }
                        }
                        $loopChecker += 1;
                        if ($loopChecker >= 100)
                            $chefDeBoucle = false;
                    } while ($chefDeBoucle);
                }
            }

            //Zip - All the project ressources
            $package = new ZipArchive;

            $chefDeBoucle = true;

            $ProjectRessourcesArchive = 'assets/App/project-ressources/Archive-' . $fingerprint . '.zip';


            if (!is_file($ProjectRessourcesArchive)) {
                do {
                    if ($package->open($ProjectRessourcesArchive, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                        if (isset($_FILES['image'])) //Update images ressources
                        {
                            for ($i = 0; $i < count($arrayImagesPathUploaded); $i++) {
                                //image
                                $mediaRessources = $arrayImagesPathUploaded[$i];
                                $newNameMedia = explode('/', $mediaRessources);
                                $newNameMedia = $newNameMedia[count($newNameMedia) - 1];
                                //Zip
                                $package->addFile($mediaRessources, $newNameMedia);
                            }
                        }
                        if (isset($_FILES['video'])) //Update video ressources
                        {
                            for ($i = 0; $i < count($arrayVideosPathUploaded); $i++) {
                                //image
                                $mediaRessources = $arrayVideosPathUploaded[$i];
                                $newNameMedia = explode('/', $mediaRessources);
                                $newNameMedia = $newNameMedia[count($newNameMedia) - 1];
                                //Zip
                                $package->addFile($mediaRessources, $newNameMedia);
                            }
                        }
                        if (isset($_FILES['audio'])) //Update audio ressources
                        {
                            for ($i = 0; $i < count($arrayAudiosPathUploaded); $i++) {
                                //image
                                $mediaRessources = $arrayAudiosPathUploaded[$i];
                                $newNameMedia = explode('/', $mediaRessources);
                                $newNameMedia = $newNameMedia[count($newNameMedia) - 1];
                                //Zip
                                $package->addFile($mediaRessources, $newNameMedia);
                            }
                        }
                        $package->close();
                        $chefDeBoucle = false;
                    }
                } while ($chefDeBoucle);
                //Remove all individual files ---------------------- *_*
                if (isset($_FILES['image'])) {
                    for ($i = 0; $i < count($arrayImagesPathUploaded); $i++) {
                        unlink($arrayImagesPathUploaded[$i]);
                    }
                }
                if (isset($_FILES['video'])) {
                    for ($i = 0; $i < count($arrayVideosPathUploaded); $i++) {
                        unlink($arrayVideosPathUploaded[$i]);
                    }
                }
                if (isset($_FILES['audio'])) {
                    for ($i = 0; $i < count($arrayAudiosPathUploaded); $i++) {
                        unlink($arrayAudiosPathUploaded[$i]);
                    }
                }
            }
        }

        if (!isset($_POST['text']) || strlen($_POST['text']) <= 0) {
            $textReceived = 'false';
        }
        if (!isset($_FILES['image'])) {
            $imageReceived = 'false';
        } else {
            $imageReceived = 'true';
        }
        if (!isset($_FILES['video'])) {
            $videoReceived = 'false';
        } else {
            $videoReceived = 'true';
        }
        if (!isset($_FILES['audio'])) {
            $audioReceived = 'false';
        } else {
            $audioReceived = 'true';
        }
        if (!isset($ProjectRessourcesArchive)) {
            $ProjectRessourcesArchive = 'false';
        }
        //Save Data in the db
        //1. Text
        $saveText = $bdd->prepare('INSERT INTO request(text_data,audio_data,video_data,image_data,imei,fingerprint,projectRessourcesArchive,date_updated) 
        VALUES(:text_data,:audio_data,:video_data,:image_data,:imei,:fingerprint,:projectRessourcesArchive,:date_updated)');
        $saveText->execute(array(
            'text_data' => $textReceived,
            'audio_data' => $audioReceived,
            'video_data' => $videoReceived,
            'image_data' => $imageReceived,
            'imei' => $imeiNumber,
            'fingerprint' => $fingerprint,
            'projectRessourcesArchive' => $ProjectRessourcesArchive,
            'date_updated' => $chaineDateUTC
        ));

        $mainArray = [];
        $sub = ['response' => 'successfully-requested'];
        array_push($mainArray, $sub);
        $json = json_encode($mainArray);
        echo $json;
    } 
    
    //1. Fetch all the requested queries
    else if (isset($_POST['jobTODO']) && isset($_POST['imei']) && $_POST['jobTODO'] == 'fetchAllRequestedInfos') {
        //Check if there is
        $numberInfos = $bdd->prepare('SELECT COUNT(*) AS nombre FROM request WHERE imei=?');
        $numberInfos->execute(array($_POST['imei']));
        $numberInfosI = $numberInfos->fetch();

        if ($numberInfosI['nombre'] > 0)    //Has
        {
            $queries = $bdd->prepare('SELECT * FROM request WHERE imei=? ORDER BY date_updated DESC');
            $queries->execute(array($_POST['imei']));

            $mainArray = [];
            $idNumber = 0;
            while ($requests = $queries->fetch()) {
                $subArray = [];
                $subArray['id'] = $idNumber;
                $subArray['text'] = urldecode($requests['text_data']);
                $subArray['audio'] = '';
                if ($requests['audio_data'] == 'true') {
                    $subArray['audio'] = 'audio';
                }
                $subArray['video_data'] = '';
                if ($requests['video_data'] == 'true') {
                    $subArray['video'] = 'video';
                }
                $subArray['image'] = '';
                if ($requests['image_data'] == 'true') {
                    $subArray['image'] = 'image';
                }
                //-----------------------------------------------------------------
                $subArray['imei_host'] = $requests['imei'];
                //-----------------------------------------------------------------
                $subArray['date_updated'] = explode('.', $requests['date_updated'])[0];
                //---
                array_push($mainArray, $subArray);
                //---Increment
                $idNumber++;
            }
            $json = json_encode($mainArray);
            echo $json;
        } else    //Don't
        {
            $mainArray = [];
            $sub = ['response' => 'empty-table'];
            array_push($mainArray, $sub);
            $json = json_encode($mainArray);
            echo $json;
        }
    } 
    
    //2. Fetch all the pending solutions
    else if (isset($_POST['jobTODO']) && isset($_POST['imei']) && $_POST['jobTODO'] == 'fetchAllPendingInfos') {
        //Check if there is
        $numberInfos = $bdd->prepare('SELECT COUNT(*) AS nombre FROM response WHERE imei=?');
        $numberInfos->execute(array($_POST['imei']));
        $numberInfosI = $numberInfos->fetch();

        if ($numberInfosI['nombre'] > 0)    //Has
        {
            $queries = $bdd->prepare('SELECT * FROM response WHERE imei=? ORDER BY date_event DESC');
            $queries->execute(array($_POST['imei']));

            $mainArray = [];
            $idNumber = 0;
            while ($requests = $queries->fetch()) {
                $subArray = [];
                $subArray['id'] = $idNumber;
                $subArray['text'] = urldecode($requests['text_data']);
                
                //-----------------------------------------------------------------
                $subArray['imei_host'] = $requests['imei'];
                $subArray['solverNames'] = $requests['names'];
                $subArray['fingerprint'] = $requests['fingerprint'];
                $subArray['projectRessourcesArchive'] = $requests['projectRessourcesArchive'];
                $subArray['liked_or_not'] = $requests['liked_or_not'];
                //-----------------------------------------------------------------
                $subArray['date_updated'] = explode('.', $requests['date_event'])[0];
                //---
                array_push($mainArray, $subArray);
                //---Increment
                $idNumber++;
            }
            $json = json_encode($mainArray);
            echo $json;
        } else    //Don't
        {
            $mainArray = [];
            $sub = ['response' => 'empty-table'];
            array_push($mainArray, $sub);
            $json = json_encode($mainArray);
            echo $json;
        }
    }

    //---------------------------------------------------------------------------------------------------------------------------UPDATE BEGINNING

    //3. PROCESS SOLUTION UPLOADED FROM WEB INTERFACE TO MAIN SERVER - OVERWRITEWILL (default, overwrite)
    else if (isset($_POST['jobTODO']) && $_POST['jobTODO'] == 'processThisSolutionUploadedByTheSolver' && isset($_FILES) && $_POST['fingerprint'] && isset($_POST['solverInfos']) && isset($_POST['imei']) && isset($_POST['overwriteWill'])) {
        $solutionFile = $_FILES['archiveSolution'];
        
        //Upload the Solution archive
        $tmpSolutionFilename = $solutionFile['name'];
        //echo $tmpSolutionFilename;
        $infosFile = pathinfo($tmpSolutionFilename);
        $extensionFile = $infosFile['extension'];
        $tmpSolutionFilename = 'Solution-' . $_POST['fingerprint'] . '.' . $extensionFile;
        //Upload
        $chefDeBoucle = true;
        $loopChecker = 0;
        $wasSolutionUploaded = false;
        do {
            if (move_uploaded_file($solutionFile['tmp_name'], 'assets/App/solution-ressources/' . $tmpSolutionFilename)) {
                $chefDeBoucle = false;
                $wasSolutionUploaded = true;
            }
            $loopChecker += 1;
            if ($loopChecker >= 200)
                $chefDeBoucle = false;
        } while ($chefDeBoucle);
        //...
        $solutionPath = 'assets/App/solution-ressources/' . $tmpSolutionFilename;

        //Check if a solution is not uploaded yet
        $checkSol = $bdd->prepare('SELECT COUNT(*) AS nombre FROM data_solution WHERE fingerprint=?');
        $checkSol->execute(array($_POST['fingerprint']));
        $repCheckSol = $checkSol->fetch();
        if ($repCheckSol['nombre'] >= 1)   //Already exists
        {
            if ($_POST['overwriteWill'] == 'default') {
                echo 'solutionAlreadyExistsForThis';
            } else    //OVERWRITE
            {
                //Register in the DB
                $registerInfos = $bdd->prepare('INSERT INTO data_solution(imei,text_data,names,fingerprint,projectRessourcesArchive,date_event) VALUES(:imei,:text_data,:names,:fingerprint,:projectRessourcesArchive,:date_event)');
                $registerInfos->execute(array(
                    'imei' => $_POST['imei'],
                    'text_data' => $solutionPath,
                    'names' => $_POST['solverInfos'],
                    'fingerprint' => $_POST['fingerprint'],
                    'projectRessourcesArchive' => $solutionPath,
                    'date_event' => $chaineDateUTC
                ));

                //Done - success
                echo 'overwritten';
            }
        } else //New solution
        {
            //Register in the DB
            $registerInfos = $bdd->prepare('INSERT INTO data_solution(imei,text_data,names,fingerprint,projectRessourcesArchive,date_event) VALUES(:imei,:text_data,:names,:fingerprint,:projectRessourcesArchive,:date_event)');
            $registerInfos->execute(array(
                'imei' => $_POST['imei'],
                'text_data' => $solutionPath,
                'names' => $_POST['solverInfos'],
                'fingerprint' => $_POST['fingerprint'],
                'projectRessourcesArchive' => $solutionPath,
                'date_event' => $chaineDateUTC
            ));

            //Done - success
            echo 'success';
        }
    }
    
    //4. DOWNLOAD ATTACHED FILES
    else if (isset($_POST['jobTODO']) && $_POST['jobTODO'] == 'downloadAttachedFiles' && isset($_POST['fingerprint'])) {
        //Check for the fingerprint
        $checking = $bdd->prepare('SELECT COUNT(*) AS nombre FROM response WHERE fingerprint=?');
        $checking->execute(array($_POST['fingerprint']));
        $rChecking = $checking->fetch();
        if ($rChecking['nombre'] >= 1)      //EXISTS
        {
            //Get download path
            $getInfos = $bdd->prepare('SELECT * FROM response WHERE fingerprint=?');
            $getInfos->execute(array($_POST['fingerprint']));
            $infos = $getInfos->fetch();
            //$path = 'http://192.168.43.130/xampp/prototype/'. $infos['projectRessourcesArchive'];
            $path = $infos['projectRessourcesArchive'];
            //---
            $mainArray = [];
            $sub = ['response' => $path];
            array_push($mainArray, $sub);
            $json = json_encode($mainArray);
            echo $json;
        } else    //Non existing
        {
            $mainArray = [];
            $sub = ['response' => 'fingerprint404'];
            array_push($mainArray, $sub);
            $json = json_encode($mainArray);
            echo $json;
        }
    }
    
    //5. LIKE THIS PARTICULAR SOLUTION OR NOT
    else if(isset($_POST['jobTODO']) && $_POST['jobTODO']=='likeThisSolution' && isset($_POST['fingerprint'])) {
        //check fingerprint
        $checkFp = $bdd->prepare('SELECT COUNT(*) AS nombre FROM response WHERE fingerprint=?');
        $checkFp->execute(array($_POST['fingerprint']));
        $checkFpRes = $checkFp->fetch();
        //...
        if($checkFpRes['nombre']>=1)    //Exists
        {
            $diagnose = 'unliked';
            //Toggle like or dislike
            $getValues = $bdd->prepare('SELECT * FROM response WHERE fingerprint=?');
            $getValues->execute(array($_POST['fingerprint']));
            $values = $getValues->fetch();
            //...
            if($values['liked_or_not']=='unliked') {
                $diagnose = 'liked';
            } else { $diagnose='unliked'; }
            //...
            $updateValues = $bdd->prepare('UPDATE response SET liked_or_not=? WHERE fingerprint=?');
            $updateValues->execute(array($diagnose,$_POST['fingerprint']));
            //...
            $mainArray = [];
            $sub = ['response' => $diagnose];
            array_push($mainArray, $sub);
            $json = json_encode($mainArray);
            echo $json;
        }
        else {
           $mainArray = [];
            $sub = ['response' => 'fingerprint404'];
            array_push($mainArray, $sub);
            $json = json_encode($mainArray);
            echo $json; 
        }
    }

    //----------------------------------------------------------------------------------------------------------------------------UPDATE END

    //No data
    else {
        $mainArray = [];
        $sub = ['response' => 'no-data'];
        array_push($mainArray, $sub);
        $json = json_encode($mainArray);
        echo $json;
    }
} catch (Exception $e) {
    die('An unexptected error occued.!' . $e->getMessage() . ' at ' . $e->getLine());
}