function centerElement(element, parentElement){
	if(parentElement===null){
		var centerWidth = screen.width/2;
		var centerHeight= screen.height/2;
	}else{
		var centerWidth = element.width/2;
		var centerHeight= element.height/2;	
	}
	
	var elementWidth = element.offsetWidth;alert(elementWidth+'a');
	var elementHeight = element.offsetHeight;
	
	element.style.left = centerWidth-elementWidth/2;
	element.style.top = centerHeight-elementHeight/2;	

}
