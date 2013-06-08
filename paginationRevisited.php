<?php
    include ("connection.php");
    include ("functions.php");
    $display_block = "";
    $remarks = "";
    $ans = "";
    $right_ans = 0;
    $wrong_ans = 0;
    $marks_all = 0;
    $final_ans = "";
    $final = "";
    $position = "";
    $self = $_SERVER['PHP_SELF'];
    $rowPerPage = 1;
    $pageNum = 1;
    if(isset($_GET['page'])) {
        $pageNum = $_GET['page'];
    }
    //counting the offset
    $offset = ($pageNum-1)*$rowPerPage;
    

    $query = "SELECT COUNT(*) AS numrows FROM question";
    $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
    while($row = mysqli_fetch_array($result)) {
        $numrows = $row['numrows'];
    }
    $offset_rand = mt_rand(0, $numrows-1);
    $maxPage = ceil($numrows/$rowPerPage);
    //$self = $_SERVER['PHP_SELF'];
    $nav = "";
    for($page=1; $page<=$maxPage; $page++) {
        if($page == $pageNum) {
            $nav .= "$page";
        } else {
            $nav .= "<a href=\"$self?page={$page}\">".$page."</a>";
        }
    }
    if($pageNum > 1) {
        $page = $pageNum - 1;
        $prev = "<a href=\"$self?page=$page\">PREV</a>";
        $first = "<a href=\"$self?page=1\">FIRST</a>";
    } else {
        $prev = '&nbsp;';
        $first = '&nbsp;';
    }
    if(isset($_GET['right']) && isset($_GET['wrong']) && isset($_GET['marks'])) {
            $right_ans = $_GET['right'];
            $wrong_ans = $_GET['wrong'];
            $marks_all = $_GET['marks'];
            $ans = find_answer2($right_ans, $wrong_ans, $marks_all, $final_ans);
            $right = $ans['right'];
            $wrong = $ans['wrong'];
            $marks = $ans['marks'];
            $final .= $ans['final'];
        } else {
            $ans = find_answer2($right_ans, $wrong_ans, $marks_all, $final_ans);
            $right = $ans['right'];
            $wrong = $ans['wrong'];
            $marks = $ans['marks'];
            $final .= $ans['final'];
        }

    if($pageNum <= $maxPage) {
        $page = $pageNum;
        
        //$next = "<a href=\"$self?page=$page\">NEXT</a>";
        //$last = "<a href=\"$self?page=$maxPage\">LAST</a>";
        $query = "SELECT * FROM question LIMIT $offset, $rowPerPage";
        $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
        while ($qs = mysqli_fetch_array($result)) {
            $question = $qs['question'];
                $optiona = $qs['optiona'];
                $optionb = $qs['optionb'];
                $optionc = $qs['optionc'];
                $optiond = $qs['optiond'];
                $answer = $qs['answer'];
                $id = $qs['id'];
            
                $display_block .= "<table id=\"quiz\">";
                $display_block .= "<tr><th>".$question."</th></tr>";
                $display_block .= "<tr><td><a href=\"$self?answer={$id}optiona&&page={$page}&&right={$right}&&wrong={$wrong}&&marks={$marks}\">".$optiona."</a></td></tr>";
                $display_block .= "<tr><td><a href=\"$self?answer={$id}optionb&&page={$page}&&right={$right}&&wrong={$wrong}&&marks={$marks}\">".$optionb."</a></td></tr>";
                $display_block .= "<tr><td><a href=\"$self?answer={$id}optionc&&page={$page}&&right={$right}&&wrong={$wrong}&&marks={$marks}\">".$optionc."</a></td></tr>";
                $display_block .= "<tr><td><a href=\"$self?answer={$id}optiond&&page={$page}&&right={$right}&&wrong={$wrong}&&marks={$marks}\">".$optiond."</a></td></tr>";
                $display_block .= "</table>";
        }
        
    } else {
        //$next = '&nbsp;';
        //$last = '&nbsp;';
        $remarks .= "<p class=\"summary\">Test Summary</p>";
                    $remarks .= "<table id=\"quiz\">";
                    $remarks .= "<tr><td>Right Answers :</td><td>".$right."</td></tr>";
                    $remarks .= "<tr><td>Wrong Answers :</td><td>".$wrong."</td></tr>";
                    $remarks .= "<tr><td>Marks Obtained :</td><td>".$marks."</td></tr>";
                    $remarks .= "</table>";
                    if($marks <= 20) {
                        $remarks .= "<p style=\"text-align:center; font-size:32px; font-weight:bold;\">Sorry! Better luck next time!!</p>";
                    } elseif ($marks > 20 && $marks <= 40) {
                        $remarks .= "<p style=\"text-align:center; font-size:32px; font-weight:bold;\">You are doing well!!</p>";
                    } else {
                        $remarks .= "<p style=\"text-align:center; font-size:32px; font-weight:bold;\">Excellent!!</p>";
                    }
    }
    //$position = $first." ".$prev." ".$nav." ".$next." ".$last;
    //$position = $prev.'&nbsp;'.'&nbsp;'.$next;
    //$ans = find_answer();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Refresh" content="<?php if(isset($_GET['answer'])){echo 0;} else {echo 14;} if($remarks) { echo 1000;} ?>; URL=paginationRevisited.php?page=<?php $page = $pageNum+1; echo $page; ?>&&right=<?php echo $right; ?>&&wrong=<?php echo $wrong; ?>&&marks=<?php echo $marks; ?>">
        <meta charset="utf-8" />
        <title></title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <style type="text/css">
            body {
                background-color: olive;
            }
            .summary {
                text-align:center; font-size:36px; font-weight:bold; background-color:#fa98c9; padding:10px; border-radius:1px; margin-top:105px; margin-bottom:-60px;
            }
            #clock {
                font-size: 30px;
                font-weight: bold;
                text-align: center;
                position: absolute;
                left: 855px;
                top : 180px;
                padding: 5px 10px;
                border-radius: 15px;
                background-color: grey;
                color: white;
            }
        </style>
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('a').click(function() {
                    $('#answer').fadeTo('slow');
                }).click(function() {
                    $('a:not(:active)').attr('disabled', 'disabled').css('background-color', 'aqua');
                }).click(function() {
                    $('a:active').css('background-color', 'black');
                }).click(function() {
                    $(this).hide('slow');
                }).click(function() {
                    $('#clock').hide('slow');
                })

            });
        </script>
        <script type="text/javascript">
         //var clock = document.getElementById('clock');
         var mins = 0;
         var secs = 16;
         function countTime() {
             secs--;
             if(secs <= 0) {
                 mins--;
                 secs = 15;
             }
             setTimeout("countTime()", 1000);
             document.getElementById('clock').innerHTML = '0' + mins + ' : ' + secs;
             if(mins == -1) {
                 countTime = false;
                 document.getElementById('clock').style.display = "none";
             }
         }
        </script>    

    </head>
    <body onload="countTime()">
       <div id="header">
        <h1 style="text-align: center; color: white; margin: 0; padding-top: 17px;">TOEFL Test</h1>
        </div>
        <div id="container">
            <form id="questions" method="post">
                <?php if($pageNum<=$maxPage) { echo "<div id=\"clock\"></div>"; } ?>
                <div id="answer"><?php echo $final; ?></div>
                <?php if($pageNum<=$maxPage){ echo $display_block;} else {echo $remarks;} ?>
                <div id="position"><?php echo $position; ?></div>
            </form>
            </div>
        <div id="footer">
        <p style="text-align: center; color: white; font-family: AmericanTypItcTEEBol; letter-spacing: 0.2em; font-size: 18px; padding-top: 25px;">&copy; copyright dhirajkarn 2012</p>
        </div>
    </body>
</html>