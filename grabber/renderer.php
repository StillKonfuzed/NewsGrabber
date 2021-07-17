<?php 

$app->any('/',function($req,$res){
    try{
        header('location:../');
    }catch(\Exception $ex){
        return $res->withJson(array('status'=>'error','message'=>$ex->getMessage()));
    }
}); 
 
$app->any('/getNews',function($req,$res){
    try{
        $con = $this->db;
        $limit = $req->getParam("limit");
        if(!empty($limit)){
            if(!ctype_digit($limit)){
                return $res->withJson(array('status'=>'hack_attempt','message'=>'fuckoff hacker, limit should be INT'));
            }else{
                //safe
                $limit = filter_var($limit,FILTER_SANITIZE_STRING);
            }
        }else{
            $limit = 100;
        }
        $downloads = $this->downloads;
        $getNews = $con->prepare("Select * from t_news where is_published = 'yes' order by news_id desc limit $limit");
        $getNews->execute();
        if($getNews->rowCount()>0){
            $newsData = $getNews->fetchall();
            foreach($newsData as $news){
                $news['full_source'] = $news['news_website'].$news['news_link'];
                $news['created_date'] = date('D d, F Y g:ia',strtotime($news['created_date']));
                if($news['news_image'] == 'placeholder.png'){
                    $news['news_image'] == $downloads.'placeholder.png';
                }else{
                    $news['news_image'] = $downloads.$news['news_image'];
                }
                unset($news['news_grabbed']);
                unset($news['is_published']);
                $collector[] = $news;
            }
            
            return $res->withJson(array('status'=>'news_found','message'=>$getNews->rowCount()." news fetched!",'newsData'=>$collector));
        }else{
            return $res->withJson(array('status'=>'no_news','message'=>$getNews->rowCount()." news fetched!",'newsData'=>null));
        }
    }catch(\Exception $ex){
        return $res->withJson(array('status'=>'error','message'=>$ex->getMessage()));
    }
});

$app->any('/getNewsById',function($req,$res){
    try{
        $con = $this->db;
        $downloads = $this->downloads;
        $news_id = $req->getParam('id');
        $news_id = filter_var($news_id,FILTER_SANITIZE_STRING);
        if(empty($news_id)){
             return $res->withJson(array('status'=>'error','message'=>'News `id` was not sent! Idk which news to show.'));exit;
        }
        $getNews = $con->prepare("Select * from t_news where is_published = 'yes' and news_id = ?");
        $getNews->execute([$news_id]);
        if($getNews->rowCount()>0){
            $newsData = $getNews->fetchall();
            foreach($newsData as $news){
                $news['full_source'] = $news['news_website'].$news['news_link'];
                $news['created_date'] = date('D d, F Y g:ia',strtotime($news['created_date']));
                if($news['news_image'] == 'placeholder.png'){
                    $news['news_image'] == $downloads.'placeholder.png';
                }else{
                    $news['news_image'] = $downloads.$news['news_image'];
                }
                unset($news['news_grabbed']);
                unset($news['is_published']);
                $collector[] = $news;
            }
            
            return $res->withJson(array('status'=>'news_found','message'=>$getNews->rowCount()." news fetched!",'newsData'=>$collector));
        }else{
            return $res->withJson(array('status'=>'no_news','message'=>$getNews->rowCount()." news fetched!",'newsData'=>null));
        }
    }catch(\Exception $ex){
        return $res->withJson(array('status'=>'error','message'=>$ex->getMessage()));
    }
});

//todo categories