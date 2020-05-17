class jsUpload
{
	imgNewDisplay()
	{
		const onFileChange = (event) => { 
			const { files }  = event.target; 
			if (!files?.length) { 
				alert("Aucun fichier n'a été selectionné"); 
				return; 
			}  
		
			// Pour chaque fichiers, on récupère un fichier à la fois.
			for(const file in files)
			{
				// On créé un FileReader 
				const reader = new FileReader();  
			
				// On créé la function qui sera exécutée lorsque l'URL de l'image aura été générée
				reader.addEventListener( 
					'load',
					() => { 
						//On récupère l'URL temporaire 
						const previewSrc = reader.result;  
						// On utilise cette URL sur un élément HTML <img /> 
						// Par exemple 
						const imageElement = document.createElement('img');
						imageElement.src = previewSrc;
						$(".images").after('<div class="form-group img-uploaded"></div>')				
						$(".images + .img-uploaded").append(imageElement);
						$(imageElement).wrapAll('<a></a>');
						$(".images + .img-uploaded a").attr("href", imageElement.src).attr("data-fancybox", "gallery");
						$(".images + .img-uploaded img").width(200);
						$(".images + .img-uploaded button").addClass("btn btn-lg btn-danger button-new-img");
					}, 
					false, 
				);  
			
				// On génère une URL temporaire de l'image en mémoire lorsqu'elle sera générée, la fonction précédente sera exécutée. 
				reader.readAsDataURL(files[file]);
			} 
		}

		if ($("#modif_annonce_images").length == 1) {  // Si l'id "#modif_annonce_images" est présent sur la page, on exécute l'écouteur d'évènement (page "Modif Annonce")
			$("#modif_annonce_images").change(onFileChange);
		}
		else {  // Sinon, on exécute l'autre écouteur d'évènement (page "Créer annonce")
			$("#creer_annonce_images").change(onFileChange);	
		}	
	}
}
const fileUpload = new jsUpload;
fileUpload.imgNewDisplay();
