<?php

$app->any('/gsmarena',function($req,$res){
   try{
       $con = $this->db;
       //start logging
       $gid = grabberDependency($con,'/gsmarena');
       $page = file_get_contents('https://www.gsmarena.com/news.php3');
        $doc = new DOMDocument();
        @$doc->loadHTML($page);
        $classname="news-item-media-wrap";
        $finder = new DomXPath($doc);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
       // print_r($spaner);
       $counter = 0;$skipper = 0; $error = 0; $imgSkipped = 0;
        foreach($spaner as $inner){
            $aTags = $inner->getElementsByTagName('a');
            foreach($aTags as $a){
                $newsSource = $a->getAttribute('href');
                $imgTag = $a->childNodes;
                $imageSrc = $imgTag->item(1)->getAttribute('src');
                $imageAlt = $imgTag->item(1)->getAttribute('alt');
                $title = $imageAlt;
                $newsSource = substr($newsSource, 0, strpos($newsSource, ".php"));
                $content = gsmContents2(urlencode($newsSource.".php"));
                
                //update News |
                $check = $con->prepare("select * from t_news where news_title = ?");
                $check->execute([$title]);
                if($check->rowCount() < 1){
                        //insert
                        $grabbedImg = file_get_contents($imageSrc,true);
                        if($grabbedImg){
                           $imageName = "GSMarena".mt_rand(000000,999999).date('D-d-m-Y-g:i:s').".jpg";
                           file_put_contents('../downloads/'.$imageName, $grabbedImg);
                        }else{
                            //retry
                            $retryImg = file_get_contents($imageSrc,true);
                            if($retryImg){
                                $imageName = "GSMarena".mt_rand(000000,999999).date('D-d-m-Y-g:i:s').".jpg";
                                file_put_contents('../downloads/'.$imageName, $retryImg);
                            }else{
                                $imageName = "placeholder.png";
                                $imgSkipped++;
                            }
                        }
                        
                        $grabbed = date('D d F Y g:ia');
                        $insertNews = $con->prepare("Insert into t_news set news_category = '1' , news_title = ? , news_image = ? , news_content = ?, news_source= ?,news_website= ?, news_link = ?, news_grabbed= ?");
                        $insertNews->execute([$title,$imageName,$content,'GSMarena','https://gsmarena.com/',$newsSource.".php",$grabbed]);
                        if($insertNews->rowCount()>0){
                            $counter++;
                        }else{
                            $error++;
                        }
                }else{
                    $skipper++;
                }
            }
        } 
        if($counter > 0){
            $status = 'grabbed';
        }else{
            $status = 'skipped';
        }
         $resp = ['status'=>$status,'grabbedNews'=>$counter,'imagesSkipped'=>$imgSkipped,'errorNews'=>$error,'skippedNews'=>$skipper];
         $refresh = grabberDependencyRefresh($con,$gid,json_encode($resp));
         return $res->withJson(array('status'=>$status,'grabbedNews'=>$counter,'imagesSkipped'=>$imgSkipped,'errorNews'=>$error,'skippedNews'=>$skipper,'logger'=>$refresh));     
   }catch(\Exception $ex){
        return $res->withJson(array('status'=>'error','message'=>$ex->getMessage()));
    }
});
//gadgets360
$app->any('/gadgets360',function($req,$res){
   try{
        $con = $this->db;
       //start logging
       $gid = grabberDependency($con,'/gadgets360');
       $page = file_get_contents('https://gadgets.ndtv.com/news');
        $doc = new DOMDocument();
        @$doc->loadHTML($page);
        $classname="caption_box";
        $finder = new DomXPath($doc);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
        $counter = 0;$skipper = 0; $error = 0; $imgSkipped = 0;
        foreach($spaner as $inner){
            $a = $inner->getElementsByTagName('a')->item(0);
                $title = $a->nodeValue; //news title
                $newsSource = $a->getAttribute('href'); //news source
                $pageInner = file_get_contents($newsSource);
                $doc2 = new DOMDocument();
                @$doc2->loadHTML($pageInner);
                $classname2="content_text";
                $finderX = new DomXPath($doc2); 
                $spanerX = $finderX->query("//*[contains(@class, '$classname2')]");
                foreach($spanerX as $innerX){
                    $innerContent = $innerX->nodeValue; //news inner content
                }
                //get image | fullstoryImage
                $pictureElem = $doc2->getElementsByTagName('picture')->item(0);
                $sourceSet = $pictureElem->childNodes[1]; //source element
                $finalImage = $sourceSet->attributes->getNamedItem("srcset")->value;
                if (strpos($innerContent, 'embed-container') !== false) {
                    $variable = substr($innerContent, 0, strpos($innerContent, "embed-container")); //remove all from source
                    $variable = str_replace("\n", '', $variable);
                    $variable = str_replace("\r", '', $variable);
                    $innerContent = str_replace("  ", '', $variable);
                }
                //update News |
                $check = $con->prepare("select * from t_news where news_title = ?");
                $check->execute([$title]);
                if($check->rowCount() < 1){
                        //insert
                        $grabbedImg = file_get_contents($finalImage,true);
                        if($grabbedImg){
                           $imageName = "Gadgets360".mt_rand(000000,999999).date('D-d-m-Y-g:i:s').".jpg";
                           file_put_contents('../downloads/'.$imageName, $grabbedImg);
                        }else{
                            //retry
                            $retryImg = file_get_contents($finalImage,true);
                            if($retryImg){
                                $imageName = "Gadgets360".mt_rand(000000,999999).date('D-d-m-Y-g:i:s').".jpg";
                                file_put_contents('../downloads/'.$imageName, $retryImg);
                            }else{
                                $imageName = "placeholder.png";
                                $imgSkipped++;
                            }
                        }
                        
                        $grabbed = date('D d F Y g:ia');
                        $insertNews = $con->prepare("Insert into t_news set news_category = '1' , news_title = ? , news_image = ? , news_content = ?, news_source= ?,news_website= ?, news_link = ?, news_grabbed= ?");
                        $insertNews->execute([$title,$imageName,$innerContent,'Gadgets360','https://gadgets360.com/',$newsSource,$grabbed]);
                        if($insertNews->rowCount()>0){
                            $counter++;
                        }else{
                            $error++;
                        }
                }else{
                    $skipper++;
                }
        } 
        if($counter > 0){
            $status = 'grabbed';
        }else{
            $status = 'skipped';
        }
        $resp = ['status'=>$status,'grabbedNews'=>$counter,'imagesSkipped'=>$imgSkipped,'errorNews'=>$error,'skippedNews'=>$skipper];
        $refresh = grabberDependencyRefresh($con,$gid,json_encode($resp));
        return $res->withJson(array('status'=>$status,'grabbedNews'=>$counter,'imagesSkipped'=>$imgSkipped,'errorNews'=>$error,'skippedNews'=>$skipper,'logger'=>$refresh));     
   }catch(\Exception $ex){
        return $res->withJson(array('status'=>'error','message'=>$ex->getMessage()));
    }
});
//
$app->any('/gadgets360Demo',function($req,$res){
   try{
        
       $page = file_get_contents('https://gadgets.ndtv.com/news');
        $doc = new DOMDocument();
        @$doc->loadHTML($page);
        $classname="caption_box";
        $finder = new DomXPath($doc);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
        //print_r($spaner);
       echo "<h1>Welcome to news grabber!</h1>";
        foreach($spaner as $inner){
            $a = $inner->getElementsByTagName('a')->item(0);
                $title = $a->nodeValue; //news title
                $newsSource = $a->getAttribute('href'); //news source
                $pageInner = file_get_contents($newsSource);
                $doc2 = new DOMDocument();
                @$doc2->loadHTML($pageInner);
                $classname2="content_text";
                $finderX = new DomXPath($doc2); 
                $spanerX = $finderX->query("//*[contains(@class, '$classname2')]");
                foreach($spanerX as $innerX){
                    $innerContent = $innerX->nodeValue; //news inner content
                }
                //get image | fullstoryImage
                $pictureElem = $doc2->getElementsByTagName('picture')->item(0);
                
                //print_r($pictureElem->childNodes[1]);
                $sourceSet = $pictureElem->childNodes[1]; //source element
                //print_r($sourceSet->attributes->getNamedItem("srcset")->value);exit; //image source
                $finalImage = $sourceSet->attributes->getNamedItem("srcset")->value;
                echo "<hr></hr>";
                echo "<p>$title</p>";
                echo "<hr></hr>";
                echo "<img src='$finalImage' width='200px'/>";
                echo "<hr></hr>";
                if (strpos($innerContent, 'embed-container') !== false) {
                    $variable = substr($innerContent, 0, strpos($innerContent, "embed-container")); //remove all from source
                    $variable = str_replace("\n", '', $variable);
                    $variable = str_replace("\r", '', $variable);
                    echo $variable = str_replace("  ", '', $variable);
                }else{
                   echo "<p>$newsSource</p>";
                }
                //embed-container 
                //echo "<p>$newsSource</p>";
                echo "<hr></hr>";
            //}
        } 
   }catch(\Exception $ex){
        return $res->withJson(array('status'=>'error','message'=>$ex->getMessage()));
    }
});

$app->any('/gsmarenaNewsGrabberDemo',function($req,$res){
   try{
      $page = file_get_contents('https://www.gsmarena.com/news.php3');
        $doc = new DOMDocument();
        @$doc->loadHTML($page);
        $classname="news-item-media-wrap";
        $finder = new DomXPath($doc);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
      $counter = 0;$skipper = 0; $error = 0;
      echo "<h1>Welcome to news Graber Demo</h1>";
      echo "<p>Grabbed news includes Full Image URL,Title,Content,Source Domain,Full Url to Original Site,Grabbed Date all in json format!</p>";
      echo "<p style='color:red'>This news is not grabbed from RSS feeds! These news are full news with their full content excluding some graphical assets from the origin site.</p>";
      echo "<a href='../' style='color:green'>See DOCS Here</a><hr></hr><hr></hr>";
      echo "<h2>Your news will be fetched from api and will be 30x faster than this.</h2><hr></hr><hr></hr>";
        foreach($spaner as $inner){
            $aTags = $inner->getElementsByTagName('a');
            foreach($aTags as $a){
                $newsSource = $a->getAttribute('href').'<br>';
                $imgTag = $a->childNodes;
                $imageSrc = $imgTag->item(1)->getAttribute('src');
                $imageAlt = $imgTag->item(1)->getAttribute('alt');
                $title = $imageAlt;
                if(file_get_contents($imageSrc)){
                  $imageSrc2 = base64_encode(file_get_contents($imageSrc));
                  $src = 'data: '.mime_content_type($imageSrc).';base64,'.$imageSrc2;
                  echo '<img src="' . $src . '" width="200px" alt="No image for this post! This Error wont occour in the api version because of retry mechanisms">';
                }else{
                    echo  "Image : No image for this post! This Error wont occour in the api version because of retry mechanisms.";
                }
                echo "<h4>Title : $imageAlt</h4>";
                $newsSource = substr($newsSource, 0, strpos($newsSource, ".php"));
                echo "<a href='https://gsmarena.com/$newsSource.php'>Source : $newsSource</a><hr></hr>";
                echo "Content : ".gsmContents2(urlencode($newsSource.".php")).'<hr></hr><hr></hr>';
            }
        } 
   } catch(\Exception $ex){
       return $res->withJson(array('status'=>'error','message'=>$ex->getMessage()));
   }
});