<?php
    $streaming=$video->getStreaming();
    echo '<audio controls preload="metadata" src="'.$streaming->generateHtmlPath().'" >Veuillez mettre à jour votre navigateur ou utiliser : Firefow, Opera ou Chrome de préférence</video>';
