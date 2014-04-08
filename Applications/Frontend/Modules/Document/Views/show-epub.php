<?php
	echo'
		<div onclick="Book.prevPage();">‹</div>
		<div id="area"></div>
		<div onclick="Book.nextPage();">›</div>

		<script>
            "use strict";
            
            document.onreadystatechange = function () {  
              if (document.readyState == "complete") {
                EPUBJS.VERSION = "0.1.6";
                
                EPUBJS.filePath = "/Scripts/Extras/epub.js-master/libs/";
                EPUBJS.cssPath = "/Scripts/Extras/epub.js-master/demo/css/";

				var Book = ePub("'.$document->getPath().'", { restore: true });
				Book.renderTo("area");
				Book.destroy();
				alert("aa");
              }  
            };
        
        </script>
	';
