<?
    $to = 'zolotarev.k@a1-reklama.ru';
    $subject = 'Заполнение формы заказа';
    $message = '
            <html>
                <head>
                    <title>'.$subject.'</title>
                </head>
                <body>
                    <p>Имя заполнившего форму: '.$_POST['name'].'</p>
                    <p>Телефон: '.$_POST['phone'].'</p>
                </body>
            </html>';
    $headers  = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: site <svai-surgut@example.com>\r\n";
    mail($to, $subject, $message, $headers);
?>