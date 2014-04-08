/*
 * Multi file upload
 */
	/*
	 * Général
	 */
	function buildAdd(begin, end){
		var i=begin;
		var addButton=$('<input>',{
				value: "Add",
				type: "button",
				click: function(){
					if(i>end)
						return null;
					
					var newName="file"+i;
					//var label=$("#file").prev().prev().html();
					var label="Fichier ";
					var newLabel=$("<label>",{
						html: label+i+" :"
					});
					
					var newInput=$("<input>", {
						 id: newName,
						 name: newName,
						 type:"file"
					});
					newInput.attr("size", "40");
					
					
					var newRemoteButton=$("<input>",{
						name: newName+"_remote",
						value: "Remote upload",
						type: "button",
						click: function(){
							var inputName=$(this).prev().attr('name');
							alert(inputName);
							var j=inputName.substr(4, inputName.length);
							
							$(this).prev().remove();//Input					
							$(this).prev().remove();//br
							$(this).prev().remove();//label
							
							//Le nouveau label
							var newBr=$("<br>",{
							});
							//Le nouveau label
							var newRemoteLabel=$("<label>",{
								html: "Url "+j+" : "
							});
							//On construit le nouvel input
							var newRemoteInput=$("<input>",{
								id: "url"+j,
								name: "url"+j,
								type: "text",
							});
							newRemoteInput.attr("size", "39");
							
							$(newRemoteInput).insertBefore(this);
							$(newBr).insertBefore(newRemoteInput);
							$(newRemoteLabel).insertBefore(newBr);
						}
					});
					
					var newDelButton=$("<input>",{
						name: newName+"_delete",
						value: "Del",
						type: "button",
						click:	function(){
							$(this).next().remove();
							$(this).prev().remove();// remoteButton	
							$(this).prev().remove();// input
							$(this).prev().remove();// br 
							$(this).prev().remove();// label
							$(this).remove();
						}
					});
					
					var newBr=$("<br/>",{});
					var newBr1=newBr.clone();
					//Label
					$(newLabel).insertBefore(this);
					//Br
					$(newBr).insertAfter(newLabel);
					//input
					$(newInput).insertAfter(newBr);
					//remote
					$(newRemoteButton).insertAfter(newInput);
					//delete
					$(newDelButton).insertAfter(newRemoteButton);
					//br
					$(newBr1).insertAfter(newDelButton);
					i++;
				}		
			});
		 return addButton;
	}
	/*
	 * Insertion
	 */
		var i=1;
		
			
		var remoteForFile=$("<input>",{
						name: "file_remote",
						value: "Remote upload",
						type: "button",
						click: function(){
							$(this).prev().remove();//Input					
							$(this).prev().remove();//br
							$(this).prev().remove();//label
							
							//Le nouveau label
							var newBr=$("<br>",{
							});
							//Le nouveau label
							var newRemoteLabel=$("<label>",{
								html: "Url : "
							});
							//On construit le nouvel input
							var newRemoteInput=$("<input>",{
								id: "url",
								name: "url",
								type: "text",
							});
							newRemoteInput.attr("size", "39");
							
							$(newRemoteInput).insertBefore(this);
							$(newBr).insertBefore(newRemoteInput);
							$(newRemoteLabel).insertBefore(newBr);
						}
					});
		$(remoteForFile).attr('required');
		
		var addButton=buildAdd(1,11);
		
		$(addButton).insertAfter( $('form[id="insert"]  input[name="file"]') );
		$(remoteForFile).insertBefore(addButton);
		$("<br/>").insertBefore( addButton );
	/*
	 * Update
	 */
		var j=11;
		for(a=0; a<10; a++){
			//Boutton de supression
			var delButton=$("<input>",{
				name: 'del-file'+a,
				value: "Delete",
				type: "button",
				click:	function(){
					
					var id=$(this).next().next().next().attr('value');
					var idList=$("#deleteListFile").attr('value');

					//On vérifie si l'id n'est pas déjà dans la liste à supprimer
					if( $.inArray(id, idList.split(","))==-1 ){
						if( $("#deleteListFile").attr('value')=="" )
							var list=id;
						else
							var list=$("#deleteListFile").attr('value')+","+id;
							
						//On ajoute l'id à la liste d'éléments à supprimer
						$("#deleteListFile").attr('value', list );
						//On supprime les boutton et les inputs
						$(this).prev().remove();// remoteButton	
						$(this).prev().remove();// remoteButton	
						$(this).prev().remove();// remoteButton	
					}
				}
			});	
			
			//Boutton de modification
			var updateButton=$("<input>",{
				name: 'update-file'+a,
				value: "Update",
				type: "button",
				click:	function(){
					//On affiche le champs d'ajout
					var id=$(this).next().next().next().next().attr('value');
					$(this).prev().css('display', 'inline');
					
					//Si l'id est dans la liste à supprimer on la supprime
					var idList=$("#deleteListFile").attr('value').split(",");
					if( $.inArray(id, idList )!=-1 ){
						var index=$.inArray(id, idList);
						var buffer="";
						for(a=0; a<idList.length; a++){
							if(a!==index){
								buffer+=idList[a]+",";
							}
						}
						buffer.substr(0, buffer.length-1);
						$("#deleteListFile").attr('value', buffer );
					}
				}
			});	
			
			var remoteForFile=$("<input>",{
				name: "file_remote"+a,
				value: "Remote upload",
				type: "button",
				click: function(){
					$(this).prev().remove();//Input					
					$(this).prev().remove();//br
					$(this).prev().remove();//label
					
					var name=$(this).attr("name");
					var nbr=name.substr(11, name.length);
					
					//Le nouveau label
					var newBr=$("<br>",{
					});
					//Le nouveau label
					var newRemoteLabel=$("<label>",{
						html: "Url : "
					});
					//On construit le nouvel input
					var newRemoteInput=$("<input>",{
						id: "url"+nbr,
						name: "url"+nbr,
						type: "text",
					});

					newRemoteInput.attr("size", "39");
					
					$(newRemoteInput).insertBefore(this);
					$(newRemoteLabel).insertBefore(newRemoteInput);
				}
			});
			$(remoteForFile).attr('required');
			
			$(delButton).insertAfter($('#file'+a));
			$(updateButton).insertAfter($('#file'+a));
			$(remoteForFile).insertAfter(updateButton);
			$('#file'+a).css('display', 'none');
		}
		
		var addButton=buildAdd(11,21);
		$(addButton).insertBefore( $('form[id="update_file"]  input[type="submit"]') );
		$("<br/>").insertAfter( addButton );
	 
