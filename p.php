<?php
        $postData       = filter_input_array(INPUT_POST);
        
        $validClients   = ['elixir.com','aandb.xyz','asl.net.in','ccsahaltd.com',];
        
        if ( !empty($postData) && 
             in_array($postData['site'], $validClients) && 
             !empty( $postData['form'] )
           )
        {
                $serverData     = filter_input_array(INPUT_SERVER);
                
                try 
                {
                        // save in db
                        $error  = false;
                        $db     = new mysqli("localhost","io","s#ad5EwruhaT","anb");
                        if ( !$db->connect_error )
                        {
                                $row['site']            = $db->real_escape_string($postData['site']);
                                $row['form_data']       = $db->real_escape_string(json_encode($postData));
                                $row['ip']              = $db->real_escape_string($serverData['REMOTE_ADDR']);

                                $sql = "INSERT INTO `customer_form_data`("
                                        . "`site`, "
                                        . "`form_data`, "
                                        . "`ip`"
                                        . ") VALUES ("
                                        . "'" . $row['site'] . "',"
                                        . "'" . $row['form_data'] . "',"
                                        . "'" . $row['ip'] . "'"
                                        . ");";
                                if ( $db->query($sql) !== true )
                                {
                                        $error = "DB Query Error: [" . $serverData['REMOTE_ADDR'] . "]: " . json_encode($postData) . PHP_EOL;
                                }
                        }
                        else
                        {
                                $error = "DB Conn Error: [" . $serverData['REMOTE_ADDR'] . "]: " . json_encode($postData) . PHP_EOL;
                        }
                        
                        if ($error)
                        {
                                // log to file
                                $file = '/var/log/p-php.log';
                                // Open the file to get existing content
                                $current = file_get_contents($file);
                                // Append a new person to the file
                                $current .= $error;
                                // Write the contents back to the file
                                file_put_contents($file, $current);
                        }
                } 
                catch (Exception $e)
                {
                        $error  = "Caught exception: [" . $serverData['REMOTE_ADDR'] . "]: " . $e->getMessage() . PHP_EOL;
                        $error .= json_encode($postData) . PHP_EOL;
                        // log to file
                        $file = '/var/log/p-php.log';
                        // Open the file to get existing content
                        $current = file_get_contents($file);
                        // Append a new person to the file
                        $current .= $error;
                        // Write the contents back to the file
                        file_put_contents($file, $current);
                }
                
                $to = [
                        'lernr.com' => [       //removed lernr from $validClients
                                                ['abari@goodeducator.com', 'Anis Bari'],
                                                ['jprakash@goodeducator.com','Jay Prakash'],
                                                ['gtiwari@goodeducator.com','Ghanshyam Tiwari'],
                                                ['kgupta@goodeducator.com','Kapil Gupta'],
                                                ['sumanparijat@gmail.com','Suman Parijat'],
                                                ['manisdeepak@gmail.com','Manis Deepak'],
                                        ],

                        'elixir.com'=>  [
                                            ['chetan@elixirholisticconsultancy.com','Chetan Jha'],
                                            ['rosalie@elixirholisticconsultancy.com','Rosalie'],
                                            ['syed@elixirholisticconsultancy.com','Syed Mamoon Hasan'],
                                        ],
                                        
                        'aandb.xyz' =>  [
                                            ['alok@aandb.xyz','Alok Shenoy'],
                                            ['syed@aandb.xyz','Syed Mamoon Hasan'],
                                            ['lalit@aandb.xyz','Lalit Patel'],
                                            ['krithika@aandb.xyz','Krithika Bharadwaj'],
                                            ['himanshu@aandb.xyz','Himanshu Singh Gurjar'],
                                        ],
                                        
                        'asl.net.in' => [
                                            ['koyanloshe@gmail.com','Alok Shenoy'],
                                            ['onlinecampaign@asl.net.in','Online Campaigns'],
                                            ['project.info@asl.net.in','Project Info'],
                                            ['lalit@aandb.xyz','Lalit Patel'],
                                        ],

                        'ccsahaltd.com' =>[ 
                                            ['himanshu@aandb.xyz','Himanshu Singh Gurjar'],
                                            ['vikramsaha@gmail.com','Vikram Saha'],
                                        ],
                ];
                
                $cc = [
                        'lernr.com' => [       
                                                //'alok@aandb.xyz',
                                                
                                        ],
                ];
                
                $bcc = [
                        'lernr.com' => [       
                                                //'alok@aandb.xyz',
                                                ['alok@aandb.xyz' , 'Alok Shenoy'],
                                                ['himanshu@aandb.xyz' , 'Himanshu Singh Gurjar'],
                                        ],
                ];
                
                $subject = [
                        'lernr.com' => "A new lead has submitted details",
                        'elixir.com' => "You have an enquiry on Elixirholisticconsultancy.com",
                        'aandb.xyz'=> "You have an enquiry on the website",
                        'asl.net.in' => "Lead for Arihant",
                        'ccsahaltd.com'=> "You have an enquiry on ccsahaltd.com",
                ];
                
                require_once '../lib/phpmailer/PHPMailerAutoload.php';
                $serverData     = filter_input_array(INPUT_SERVER);
                
                $message = "Hello," . PHP_EOL . PHP_EOL . "Please find the details below:" . PHP_EOL . PHP_EOL;

                foreach ($postData['form'] as $v)
                {
                        foreach ($v as $fieldName => $fieldValue)
                        {
                                $message .= $fieldName . ": " . $fieldValue . PHP_EOL;
                                
                                if ($fieldName == "Name")
                                {
                                        $fromName  = trim($fieldValue);
                                }
                                
                                if ($fieldName == "Email")
                                {
                                        $fromEmail = trim($fieldValue);
                                }
                        }
                        $message .= PHP_EOL;
                }
                
                if ( !empty( $postData['utm'] ) )
                {
                        foreach ($postData['utm'] as $fieldName => $fieldValue)
                        {
                                $message .= $fieldName . ": " . $fieldValue . PHP_EOL;
                        }
                }

                $message .= PHP_EOL;

                $message .= "IP: " . $serverData['REMOTE_ADDR'] . PHP_EOL;

                $message .= PHP_EOL;

                $message .= "Regards," . PHP_EOL . "The A&B Team";
                
                
                //Create a new PHPMailer instance
                $mail = new PHPMailer;
                $mail->CharSet = 'utf-8';
                $mail->IsSendmail();
                
                //Set who the message is to be sent from
                $mail->setFrom('no-reply@aandb.xyz', 'AandB Team');
                
                //Set an alternative reply-to address
                if (isset($fromName) && isset($fromEmail))
                {
                        $mail->addReplyTo($fromEmail, $fromName);
                }
                
                //Set who the message is to be sent to
                if (isset($to[$postData['site']]) && !empty($to[$postData['site']]))
                {
                        foreach ($to[$postData['site']] as $arr)
                        {
                                if ( isset( $arr[1] ) )
                                {
                                        $mail->addAddress($arr[0], $arr[1]);
                                }
                                else
                                {
                                        $mail->addAddress($arr[0]);
                                }
                        }
                }
                else if ( isset( $postData['errorURL'] ) )
                {
                        header('Location: ' . $postData['errorURL'], true, 302);
                }
                else
                {
                        exit;
                }
                
                if (isset($cc[$postData['site']]) && !empty($cc[$postData['site']]))
                {
                        foreach ($cc[$postData['site']] as $arr)
                        {
                                if ( isset( $arr[1] ) )
                                {
                                        $mail->addCC($arr[0], $arr[1]);
                                }
                                else
                                {
                                        $mail->addCC($arr[0]);
                                }
                        }
                }
                
                if (isset($bcc[$postData['site']]) && !empty($bcc[$postData['site']]))
                {
                        foreach ($bcc[$postData['site']] as $arr)
                        {
                                if ( isset( $arr[1] ) )
                                {
                                        $mail->addBCC($arr[0], $arr[1]);
                                }
                                else
                                {
                                        $mail->addBCC($arr[0]);
                                }
                        }
                }
                
                //Set the subject line
                $mail->Subject = isset($subject[$postData['site']]) ? $subject[$postData['site']] : "";
                
                $mail->WordWrap = 50;                                 // set word wrap to 50 characters
                $mail->IsHTML(true);
                $mail->Body    = nl2br($message);
                
                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                //$mail->msgHTML( nl2br( $message ) );
                
                //Replace the plain text body with one created manually
                $mail->AltBody = $message;
                
                $mail->XMailer = ' ';
                
                //send the message, check for errors
                if (!$mail->send())
                {
                        try 
                        {
                                // save in db
                                $error  = false;
                                if ($db == false)
                                {
                                        // try to reconnect
                                        $db     = new mysqli("localhost","io","s#ad5EwruhaT","anb");
                                }
                                
                                if ( !$db->connect_error )
                                {
                                        $row                    = [];
                                        $row['post_data']       = $postData;
                                        $row['ip']              = $serverData['REMOTE_ADDR'];

                                        $sql = "INSERT INTO `error_log`("
                                                . "`msg`"
                                                . ") VALUES ("
                                                . "'" . $db->real_escape_string(json_encode($row)) . "'"
                                                . ");";
                                        if ( $db->query($sql) !== true )
                                        {
                                                $error = "DB Query Error [" . $serverData['REMOTE_ADDR'] . "]: " . $row . PHP_EOL;
                                        }
                                }
                                else
                                {
                                        $error = "DB Conn Error: [" . $serverData['REMOTE_ADDR'] . "]: " . json_encode($postData) . PHP_EOL;
                                }

                                if ($error)
                                {
                                        // log to file
                                        $file = '/var/log/p-php.log';
                                        // Open the file to get existing content
                                        $current = file_get_contents($file);
                                        // Append a new person to the file
                                        $current .= $error;
                                        // Write the contents back to the file
                                        file_put_contents($file, $current);
                                }
                        } 
                        catch (Exception $e)
                        {
                                $error  = "Caught exception: [" . $serverData['REMOTE_ADDR'] . "]: " . $e->getMessage() . PHP_EOL;
                                $error .= json_encode($postData) . PHP_EOL;
                                // log to file
                                $file = '/var/log/p-php.log';
                                // Open the file to get existing content
                                $current = file_get_contents($file);
                                // Append a new person to the file
                                $current .= $error;
                                // Write the contents back to the file
                                file_put_contents($file, $current);
                        }
                        
                        if ( isset( $postData['errorURL'] ) )
                        {
                                header('Location: ' . $postData['errorURL'], true, 302);
                        }
                        else
                        {
                                echo "Mailer Error: " . $mail->ErrorInfo;
                        }
                } 
                else
                {
                        if ( isset( $postData['returnURL'] ) )
                        {
                                header('Location: ' . $postData['returnURL'], true, 302);
                        }
                        else
                        {
                                echo "Message sent!";
                        }
                }
                
                
        }

?>