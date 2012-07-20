<?php
    exec("java -jar /var/www/detect/DetectPlagiarism.jar 1");
    echo "Finished<br>";
?>

<a href="output/report.html">Result</a>
