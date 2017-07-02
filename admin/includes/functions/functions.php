<?php

        // Function to get Title of Current Page

        function getTitleInHeader(){

            global $titleHeader;

            if(isset($titleHeader)){
                echo $titleHeader;
            }else{
                echo "Default";
            }
        }


        // Function to Redirsct to HomePage
        /* After Update This Function
        ** Function Accept Parameters ($MSG, $URL, $SECONDS )
         * MSG like [ Success | Error | Warning ]
         * URL  like  [ index.php | HTTP_REFERER ]
        */

        function RedirectToHome($MSG, $url = null, $second = 3){
            // check from url
            if($url == null){
                $url = 'index.php';
                $link = "Home Page";
            }else{
                // check if HTTP_REFRERE  is Exist or no
                if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''){
                    $url = $_SERVER['HTTP_REFERER'];
                    $link = "Previous Page";
                }else{
                    $url = 'index.php';
                    $link = "Home Page";
                }
            }
            echo $MSG;
            echo "<div class='alert alert-info'>You Will Redierct To $link in {$second} Seconds</div>";

            header("refresh:$second;url=$url");
            exit();
        }


        /* Function to Check if This Item Exist in DB or No
         * Accept Parameters
         * $select_what >> What you Want to Select like [ * | Username | CategoryID ]
         * $tblName  Table name
         * $value >>  This Value which Equiavlent with $select_what [Ahmed | Ali | Mobile_category | PC_category]
         * Will Return Number of Column Fetched
        */
        function checkItem($select_what, $tblName, $value){
            global $conn;

            $stmt = $conn->prepare("SELECT $select_what FROM $tblName WHERE $select_what = ? ");
            $stmt->execute(array($value));
            $count = $stmt->rowCount();
            return $count;

        }


        /* Function to Check if This Item Exist in DB or No
         * Accept Parameters
         * $select_what >> What you Want to Select like [ * | Username | CategoryID ]
         * $tblName  Table name
         * $value >>  This Value which Equiavlent with $select_what [Ahmed | Ali | Mobile_category | PC_category]
         * Will Return Number of Column Fetched
        */
        function checkItemAtUpdate($tblName, $item_name, $item_id, $value_name, $value_id){

            global $conn;
            $stmt = $conn->prepare("SELECT * FROM $tblName WHERE $item_name = ? AND $item_id != ? ");
            $stmt->execute(array($value_name, $value_id));
            $count = $stmt->rowCount();
            return $count;
        }


        /*
         * Function to Calculate number of Rows in Table
         * Accept Parameter
         * Count_what >>  Cunt Current Row or All [ * |  Username | CategoryID ]
         * $tblname >> Table Name
         */

        function calculateItems($count_what, $tblName, $more = null){
            global $conn;
            // if We Want to Add Query in $stmt->prepare()  Like [ Where Username = ahmed ]
            if($more == null){
                $query = '';
            }else{
                $query = $more;
            }
            $stmt = $conn->prepare("SELECT COUNT($count_what) FROM $tblName $query");
            $stmt->execute();
            $row_count = $stmt->fetchColumn();

            return $row_count;
        }

        /*
         * get latest Items
         * Accept parameters
         * $select_what >> what you want to select [ * | Username ]
         * $tblName >> Table Name
         * $order_by >> what the main column to Order this Items
         * $limit  >> number of Items will execute
         * */

        function getLatest($select_what, $tblName, $order_by, $limit = 5){
            global $conn;
            $stmt = $conn->prepare("SELECT $select_what FROM $tblName ORDER BY $order_by DESC LIMIT $limit");
            $stmt->execute();
            $count = $stmt->fetchAll();
            return $count;
        }

