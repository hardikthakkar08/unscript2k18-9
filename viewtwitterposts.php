<?php

session_start();
if(!isset($_SESSION['loggedInUser'])){
    
    //send them to login page
    header('Loction: index.php');
}

include('includes/connections.php');





if(isset($_GET['bot'])) {
	
	
$file_tweets = file_get_contents("showtweets.php");
//json string to array
$tweets=json_decode($file_tweets, false);
$tweet_result = array();
$index = 0;

$id=0;


if(isset($_GET['from']) && isset($_GET['to'])){
		$id=0;	
		foreach($tweets as $tweet){
		$id++;	
		if(	$id >= $_GET['from'] && $id <= $_GET['to']){
		$tweet_result[$index]['id'] = $tweet->id_str;
		$tweet_result[$index]['createdAt'] = $tweet->created_at;
		$tweet_result[$index]['text'] = $tweet->text;
		$tweet_result[$index]['name'] = $tweet->user->name;
		$tweet_result[$index]['screen_name'] = $tweet->user->screen_name;
		$tweet_result[$index]['profileImageurl'] = $tweet->user->profile_image_url;
		$tweet_result[$index]['favorited'] = $tweet->favorited;
		$tweet_result[$index]['favorite_count'] = $tweet->favorite_count;
		$tweet_result[$index]['location'] = $tweet->user->location;
		$index++;
		}
		}		
}

else if(isset($_GET['limit'])){
	$id=0;
	foreach($tweets as $tweet){
		
		$id++;	
		if(	$id <= $_GET['limit']){
		$tweet_result[$index]['id'] = $tweet->id_str;
		$tweet_result[$index]['createdAt'] = $tweet->created_at;
		$tweet_result[$index]['text'] = $tweet->text;
		$tweet_result[$index]['name'] = $tweet->user->name;
		$tweet_result[$index]['screen_name'] = $tweet->user->screen_name;
		$tweet_result[$index]['profileImageurl'] = $tweet->user->profile_image_url;
		$tweet_result[$index]['favorited'] = $tweet->favorited;
		$tweet_result[$index]['favorite_count'] = $tweet->favorite_count;
		$tweet_result[$index]['location'] = $tweet->user->location;
		$index++;
		}
		
	}
}
	else{
		
	$id=0;
	foreach($tweets as $tweet){
		
		$id++;	
		if(	$id <= 5){
		$tweet_result[$index]['id'] = $tweet->id_str;
		$tweet_result[$index]['createdAt'] = $tweet->created_at;
		$tweet_result[$index]['text'] = $tweet->text;
		$tweet_result[$index]['name'] = $tweet->user->name;
		$tweet_result[$index]['screen_name'] = $tweet->user->screen_name;
		$tweet_result[$index]['profileImageurl'] = $tweet->user->profile_image_url;
		$tweet_result[$index]['favorited'] = $tweet->favorited;
		$tweet_result[$index]['favorite_count'] = $tweet->favorite_count;
		$tweet_result[$index]['location'] = $tweet->user->location;
		$index++;
		}
		
	}
		
		
	}

	



echo json_encode($tweet_result);	
	
}
else{
include_once('includes/header.php');
?>	

<!DOCTYPE html >

<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Tweets</title>


        <!-- Bootstrap Core CSS -->
        <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">

        <!-- Bootstrap Core CSS -->
        <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">


 

      <!-- jquery -->
     <script src="jquery/dist/jquery.js"></script>


             <!-- jquery -->
             <script src="jquery/dist/jquery.min.js"></script>


       <!-- Bootstrap js -->
        <script src="bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Bootstrap js -->
        <script src="bootstrap/dist/js/bootstrap.js"></script>

<style>

.modal-body{
  padding-left: 30px;
  padding-right: 30px;
}

</style>


</head>


<body>


    <div class="container-fluid">
    <br>

        <input type="text" id="posttext"><input type="button" id="posttweet" value="Post Tweet"><br><br><br>
       <!-- <input type="button" id="showtweets" value="Show Tweet"> -->


        <div class="row" id="tweets">
		
</div>
 		<!--./row -->
  

       
      
<

    </div>
    <!--./container-fluid -->

<script>

        $(document).ready(function(){

            //$("#showtweets").click(function(){

                //console.log("on click")
           

                console.log("on click")
                var tweets="";
                console.log("i am");

                $.ajax({
                    type:"get",
                    url: "showtweets.php",
                    success:function(data)
                    {
                        var mydata=JSON.parse(data);

                        $.each(mydata, function(i, f) {
                            var icon=''
                            var hashtag="";
                            for(var i=0;i < f.entities.hashtags.length;i++){
                                hashtag=hashtag+"#"+JSON.stringify(f.entities.hashtags[i].text)+" ";
                            }
                            console.log(f.favourite_count);
                            if(f.favourite_count > 0){
                                var icon='style="border-color:red"';
                            }
                            else{
                                icon='';
                            }
                            //console.log(f.id);
                            tweets=tweets+'<div class="col-lg-4"><div class="panel panel-primary" '+icon+'><div class="panel-heading">'+f.user.name+'(@'+f.user.screen_name+')'+
                                '</div><div class="panel-body" style="height:200px">'+
                                f.text
                                +'</div><div class="panel-footer" style="height:50px">'+
                               hashtag.replace(/\"/g, "")
                                +'</div></div></div>'
                        })
                        //console.log(tweets);
                        document.getElementById("tweets").innerHTML=tweets;

                    }
            
        });

        $("#posttweet").click(function(){

            var mytweet=$("#posttext").val();
            console.log(mytweet);
            var tweets="";
            console.log("i am");

            $.ajax({
                type:"post",
                url: "postmessage.php",
                data:{
                    mytweet:mytweet
                },
                success:function(response)
                {
                    console.log("got data");
                    var mydata=JSON.parse(response);

                    $.each(mydata, function(i, f) {
                        var icon=''
                        var hashtag="";
                        for(var i=0;i < f.entities.hashtags.length;i++){
                            hashtag=hashtag+"#"+f.entities.hashtags[i]+" ";
                        }
                        console.log(f.favourite_count);
                        if(f.favourite_count > 0){
                            var icon='style="border-color:red"';
                        }
                        else{
                            icon='';
                        }
                        //console.log(f.id);
                        tweets=tweets+'<div class="col-lg-4"><div class="panel panel-primary" '+icon+'><div class="panel-heading">'+f.user.name+'('+f.user.screen_name+')'+
                            '</div><div class="panel-body" style="height:200px">'+
                            f.text
                            +'</div><div class="panel-footer" style="height:50px">'+

                            hashtag+'</div></div></div>'
                    })
                    console.log(tweets);
                    document.getElementById("tweets").innerHTML=tweets;
                }
            });
        });



});





</script>


</body>
</html>
<?php
}
?>