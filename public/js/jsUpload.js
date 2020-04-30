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
			//const file = files[0];  
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
						$(".img-uploaded:first-of-type").append(imageElement);
						$(imageElement).wrapAll('<a></a>');
						$(".img-uploaded:first-of-type a").attr("href", imageElement.src).attr("data-fancybox", "gallery");
						$(".img-uploaded:first-of-type img").width(200);
						$(".img-uploaded:first-of-type a").after('<button>Supprimer</button>');
						$(".img-uploaded:first-of-type button").addClass("btn btn-lg btn-danger button-new-img");
					}, 
					false, 
				);  
			
				// On génère une URL temporaire de l'image en mémoire lorsqu'elle sera générée, la fonction précédente sera exécutée. 
				reader.readAsDataURL(files[file]);
			} 
		}
		$("#modif_annonce_images").change(onFileChange);
		
		$('.button-new-img').click((event) => {
			event.preventDefault();
			delete files[file];
		});
	}
}
const fileUpload = new jsUpload;
fileUpload.imgNewDisplay();
