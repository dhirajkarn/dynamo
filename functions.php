<?php
    include ("connection.php");
    
    function display_question($question_id = 1) {
        global $mysqli;
        $display_block = "";
        $query = "SELECT * FROM question WHERE id = {$question_id}";
        $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
        while($qs = mysqli_fetch_array($result)) {
            $question = $qs['question'];
            $optiona = $qs['optiona'];
            $optionb = $qs['optionb'];
            $optionc = $qs['optionc'];
            $optiond = $qs['optiond'];
            $answer = $qs['answer'];
            $id = $qs['id'];
            
            $display_block .= "<table id=\"quiz\">";
            if($id == 1) {
                $display_block .= "<tr><td>"."All Questions carry equal marks!</td></tr>";
            }
            $display_block .= "<tr><th>".$id.") ".$question."</th></tr>";
            $display_block .= "<tr><td><a href=\"dynamo.php?answer={$id}optiona\">".$optiona."</a></td></tr>";
            $display_block .= "<tr><td><a href=\"dynamo.php?answer={$id}optionb\">".$optionb."</a></td></tr>";
            $display_block .= "<tr><td><a href=\"dynamo.php?answer={$id}optionc\">".$optionc."</a></td></tr>";
            $display_block .= "<tr><td><a href=\"dynamo.php?answer={$id}optiond\">".$optiond."</a></td></tr>";
            $display_block .= "</table>";
            //$display_block .= "<input type=\"submit\" id=\"next_button\" name=\"submit\" value=\"Next\">";
            //$question_id++;
        }
        return $display_block;
    }

    function find_answer() {
        global $mysqli;
        $final = "";
        if(isset($_GET['answer'])) {
            $my_id = $_GET['answer'];
            $sel_id = intval($my_id);
            if($sel_id > 99 && $sel_id < 1000) {
               $my_ans = substr($my_id, 3);
            } elseif($sel_id > 9 && $sel_id < 100) {
               $my_ans = substr($my_id, 2);
            } elseif($sel_id > 0 && $sel_id < 10) {
               $my_ans = substr($my_id, 1);
            } else {
               echo "Not a valid string.";
            }
                $sql = "SELECT * FROM question WHERE id={$sel_id}";
                $res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                if($res) {
                    while($q = mysqli_fetch_array($res)){
                        $q_id = $q['id'];
                        $q_ans = $q['answer'];   
                    }
                    if(strcmp($my_ans, $q_ans) == 0) {
                        //$right += 1;
                        //$marks += 10;
                        $final .= "Correct";
                    } else {
                        //$wrong += 1;
                        $final .= "Incorrect"." "."<img src=\"cross.png\">";
                    }
                } else {
                    
                }
        }
        
        return $final; 
    }

    function find_answer2($right=0,$wrong=0,$marks=0,$final="") {
        global $mysqli;
        $final = "";
        if(isset($_GET['answer'])) {
            $my_id = $_GET['answer'];
            $sel_id = intval($my_id);
            if($sel_id > 99 && $sel_id < 1000) {
               $my_ans = substr($my_id, 3);
            } elseif($sel_id > 9 && $sel_id < 100) {
               $my_ans = substr($my_id, 2);
            } elseif($sel_id > 0 && $sel_id < 10) {
               $my_ans = substr($my_id, 1);
            } else {
               echo "Not a valid string.";
            }
                $sql = "SELECT * FROM question WHERE id={$sel_id}";
                $res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                if($res) {
                    while($q = mysqli_fetch_array($res)){
                        $q_id = $q['id'];
                        $q_ans = $q['answer'];   
                    }
                    if(strcmp($my_ans, $q_ans) == 0) {
                        $right += 1;
                        $marks += 10;
                        $final .= "Correct"." "."<img src=\"Red.png\" height=\"20\" width=\"20\">";
                    } else {
                        $wrong += 1;
                        $final .= "Incorrect"." "."<img src=\"Delete.png\"  height=\"20\" width=\"20\">";
                    }
                } else {
                    
                }
        }
        $all = array('right' => $right, 'wrong' => $wrong, 'marks' => $marks, 'final' => $final);
        return $all; 
    }
?>
