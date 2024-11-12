<!DOCTYPE html>
<html lang="en">
<head>
  <title>UML Editor</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Maciej Szymczak">  
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
  <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container" id="editor">
  <div class="row my-4">
    <div class="col">
      <div class="jumbotron">
        <h1>UML Editor</h1>
		<a href="https://soft.home.pl"><p class="lead">by Mac</p></a>
		<p class="text-secondary">Data privacy statement: This tool is not collecting any data. This tools forwards the data to https://plantuml.com/ which is not collecting data too.</p>
      </div>
    </div>
  </div>

  <div class="row my-2">
    <div class="col">
	<textarea class="form-control" id="umlText" name="outputText" rows="15">
@startgantt


ganttscale monthly


Project starts 2023-03-01


[AAA] starts 2023-05-01 and ends 2024-04-30
[BBB] starts 2024-02-01 and ends 2024-04-30
[CCC] starts 2024-05-01 and ends 2024-07-31


@endgantt	
	</textarea>
	<button type="button" class="btn btn-outline-primary my-2" id="refresh-button">Refresh</button>
	<button type="button" class="btn btn-outline-primary my-2" id="link-button">Link</button>
	<button type="button" class="btn btn-outline-primary my-2" id="examples-button">Examples</button>
	<button type="button" class="btn btn-outline-primary my-2" id="more-button">More examples</button>
	<button type="button" class="btn btn-outline-primary my-2" id="syntax-button">Syntax</button>
    </div>
  </div>

</div>
<div class="container">
    <div id="image-container"></div>
</div>
    <script>
		async function zipText(text) {
			// Encode the text to a byte array
			const encoder = new TextEncoder();
			const uint8Array = encoder.encode(text);

			// Create a CompressionStream
			const stream = new CompressionStream('gzip');
			const writer = stream.writable.getWriter();

			// Write the encoded text to the CompressionStream
			writer.write(uint8Array);
			writer.close();

			// Collect the compressed chunks into an array
			const compressedChunks = [];
			const reader = stream.readable.getReader();

			while (true) {
				const { value, done } = await reader.read();
				if (done) break;
				compressedChunks.push(value);
			}

			// Concatenate all chunks into a single Uint8Array
			const compressedArray = new Uint8Array(compressedChunks.reduce((acc, val) => acc + val.length, 0));
			let offset = 0;
			for (const chunk of compressedChunks) {
				compressedArray.set(chunk, offset);
				offset += chunk.length;
			}

			// Convert to base64 for URL-safe transmission
			const base64String = btoa(String.fromCharCode(...compressedArray));
			return encodeURIComponent(base64String);
		}

		// Function to unzip (decompress) text
		async function unzipText(encodedBase64String) {
			// Decode the URL-encoded base64 string
			const base64String = decodeURIComponent(encodedBase64String);

			// Convert base64 string to Uint8Array
			const compressedArray = Uint8Array.from(atob(base64String), c => c.charCodeAt(0));

			// Create a DecompressionStream
			const stream = new DecompressionStream('gzip');
			const writer = stream.writable.getWriter();

			// Write the compressed array to the DecompressionStream
			writer.write(compressedArray);
			writer.close();

			// Collect the decompressed chunks into an array
			const decompressedChunks = [];
			const reader = stream.readable.getReader();

			while (true) {
				const { value, done } = await reader.read();
				if (done) break;
				decompressedChunks.push(value);
			}

			// Concatenate all chunks into a single Uint8Array
			const decompressedArray = new Uint8Array(decompressedChunks.reduce((acc, val) => acc + val.length, 0));
			offset = 0;
			for (const chunk of decompressedChunks) {
				decompressedArray.set(chunk, offset);
				offset += chunk.length;
			}

			// Decode the byte array back to text
			const decoder = new TextDecoder();
			return decoder.decode(decompressedArray);
		}
		
        async function renderPicture(umlText) {
            try {
                
				//Way 1: This way is working but is limited by max len of url
				//const encodedUrl = encodeURIComponent(umlText);
				//const response = await fetch('https://soft.home.pl/tools/plantumlAPI.php?txt='+encodedUrl);				

				//Way 2: Unlimited lenght of the string
				const url = 'https://soft.home.pl/tools/plantumlAPI.php';
				const data = {
				  txt: umlText
				};
				const response = await fetch(url, {
					method: 'POST', // *GET, POST, PUT, DELETE, etc.
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded' //text/plain, application/json
					},
					body: new URLSearchParams(data) // body data type must match "Content-Type" header
				});				

				
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const text = await response.text();
				console.log(text);

                // Create a temporary DOM element to parse the response
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = text;

                // Extract all <img> elements
                const imgElements = tempDiv.getElementsByTagName('img');
                
                // Append the <img> elements to the image container
                const imageContainer = document.getElementById('image-container');
				
				imageContainer.innerHTML = '';
                for (const img of imgElements) {
                    imageContainer.appendChild(img.cloneNode());
                }
            } catch (error) {
                console.error('Error fetching and displaying images:', error);
            }
        }
		
        document.getElementById('refresh-button').addEventListener('click', () => {
            const umlText = document.getElementById('umlText').value;
            renderPicture(umlText);
        });

        document.getElementById('link-button').addEventListener('click', async () => {
			const umlText = document.getElementById('umlText').value;
			const zippedText = await zipText(umlText);
			const encodedUrl = encodeURIComponent( zippedText );
            const url = "https://soft.home.pl/tools/plantuml.php?hideEditor=yes&zip=yes&umlText="+encodedUrl;
            window.open(url, '_blank');
        });

		
        document.getElementById('examples-button').addEventListener('click', () => {
            const url = "https://real-world-plantuml.com/";
            window.open(url, '_blank');
        });

        document.getElementById('more-button').addEventListener('click', () => {
            const url = "https://www.planttext.com/";
            window.open(url, '_blank');
        });

        document.getElementById('syntax-button').addEventListener('click', () => {
            const url = "https://plantuml.com";
            window.open(url, '_blank');
        });
				
		document.addEventListener('DOMContentLoaded', async (event) => {
			// Get the current URL
			const url = new URL(window.location.href);

			// Get the search parameters
			const params = new URLSearchParams(url.search);

			const hideEditor = params.get('hideEditor');
			if (hideEditor=='yes') {
				const myDiv = document.getElementById("editor");
				myDiv.style.display = "none";
			}

			const zip = params.get('zip');

			// Get the 'umlText' parameter
			const umlTextEncoded = params.get('umlText');

			if (umlTextEncoded) {
				// Decode the parameter
				const umlTextDecoded = 
						zip=='no'
						? await decodeURIComponent(umlTextEncoded)
						: await unzipText(decodeURIComponent(umlTextEncoded));

				// Set the decoded text to the textarea
				const textArea = document.getElementById('umlText');
				if (textArea) {
					textArea.value = umlTextDecoded;
				}
			}

			// Call the function to fetch and display images
			const umlText = document.getElementById('umlText').value;
			renderPicture(umlText);
			
		});		
		
    </script>
	
</body>
</html>