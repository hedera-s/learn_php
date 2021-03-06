<?php
/*************************************************************************************/


				/**
				*
				*	Entschärft und bereinigt die Whitespaces eines String
				*
				*	@param String		$inputString	Der zu entschärfende und zu bereinigende String
				*
				*	@return String							Der entschärfte und bereinigte String
				*
				*/
				function cleanString($inputString) {
if(DEBUG_F)		echo "<p class='debugCleanString'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __FUNCTION__ . "($inputString) <i>(" . basename(__FILE__) . ")</i></p>";					
					
					// trim() entfernt am Anfang und am Ende eines Strings alle 
					// sog. Whitespaces (Leerzeichen, Tabulatoren, Zeilenumbrüche)
					$inputString = trim($inputString);
					
					// htmlspecialchars() entschärft HTML-Steuerzeichen wie < > & '' ""
					// und ersetzt sie durch &lt;, &gt;, &amp;, &apos; &quot;
					$inputString = htmlspecialchars($inputString);
					
					// bereinigten und entschärften String zurückgeben
					return $inputString;
				}

				
/*************************************************************************************/


				/**
				*
				*	Prüft einen String auf Leerstring, Mindest- und Maximallänge
				*
				*	@param String	$inputString		Der zu prüfende String
				*	@param [Int		$minLength]			Die erforderliche Mindestlänge
				*	@param [Int		$maxLength]			Die erlaubte Maximallänge
				*
				*	@return String/NULL					Ein String bei Fehler, ansonsten NULL
				*
				*/
				function checkInputString($inputString, $minLenght=INPUT_MIN_LENGTH, $maxLength=INPUT_MAX_LENGTH) {					
if(DEBUG_F)		echo "<p class='debugCheckInputString'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __FUNCTION__ . "($inputString, [$minLenght], [$maxLength]) <i>(" . basename(__FILE__) . ")</i></p>";					
					
					$errorMessage = NULL;
					
					// Prüfen auf Leerstring
					if( $inputString === "" ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";
					
					// Prüfen auf Mindestlänge
					} elseif( mb_strlen($inputString) < $minLenght ) {
						$errorMessage = "Muss mindestens $minLenght Zeichen lang sein!";
					
					// Prüfen auf Maximallänge
					} elseif( mb_strlen($inputString) > $maxLength ) {
						$errorMessage = "Darf maximal $maxLength Zeichen lang sein!";
					}
					
					return $errorMessage;
					
				}


/*************************************************************************************/


				/**
				*
				*	Prüft, ob ein übergebener String eine valide Email-Adresse enthält
				*
				*	@param String		$inputString		Der zu prüfende String
				*
				*	@return String/NULL						Ein String bei Fehler, ansonsten NULL
				*
				*/
				function checkEmail($inputString) {
if(DEBUG_F)		echo "<p class='debugCheckEmail'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __FUNCTION__ . "($inputString) <i>(" . basename(__FILE__) . ")</i></p>";	
					$errorMessage = NULL;

					// Prüfen auf Leerstring
					if( $inputString === "" ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";
					
					// Email auf Validität prüfen
					} elseif( !filter_var($inputString, FILTER_VALIDATE_EMAIL) ) {
						$errorMessage = "Dies ist keine gültige Email-Adresse!";
					}

					return $errorMessage;
					
				}


/*************************************************************************************/


				/**
				*
				*	Speichert und prüft ein hochgeladenes Bild auf MIME-Type, Datei- und Bildgröße
				*
				*	@param Array	$uploadedImage			Die Bildinformationen aus $_FILES
				*	@param [Int		$maxWidth]				Die maximal erlaubte Bildbreite in px
				*	@param [Int		$maxHeight]				Die maximal erlaubte Bildhöhe in px
				*	@param [Int		$maxSize]				Die maximal erlaubte Dateigröße in Byte
				*	@param [String	$uploadPath]			Das Speicherverzeichnis auf dem Server
				*	@param [Array	$allowedMimeTypes]	Whitelist der erlaubten MIME-Types
				*
				*	@return Array {String/NULL				Fehlermeldung im Fehlerfall/ ansonsten NULL
				*						String					Der Server-Pfad zum gespeicherten Bild}
				*
				*/
				function imageUpload($uploadedImage, 
											$maxWidth			= IMAGE_MAX_WIDTH, 
											$maxHeight			= IMAGE_MAX_HEIGHT, 
											$maxSize			= IMAGE_MAX_SIZE,
											$uploadPath			= IMAGE_UPLOAD_PATH,
											$allowedMimeTypes 	= IMAGE_ALLOWED_MIMETYPES
											) {
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __FUNCTION__ . "() <i>(" . basename(__FILE__) . ")</i></p>";	
					
					/*
						Das Array $_FILES['avatar'] bzw. $uploadedImage enthält:
						Den Dateinamen [name]
						Den generierten (also ungeprüften) MIME-Type [type]
						Den temporären Pfad auf dem Server [tmp_name]
						Die Dateigröße in Bytes [size]
					*/


					/********** BILDINFORMATIONEN SAMMELN **********/
					
					// Dateiname
					$fileName		= $uploadedImage['name'];
					// ggf. Leerzeichen im Dateinamen durch "_" ersetzen
					$fileName 		= str_replace(" ", "_", trim($fileName) );
					// Dateinamen in Kleinbuchstaben umwandeln
					$fileName 		= strtolower($fileName);
					
					// Dateigröße
					$fileSize		= $uploadedImage['size'];
					
					// Temporärer Pfad auf dem Server
					$fileTemp		= $uploadedImage['tmp_name'];
					
					// zufälligen Dateinamen generieren
					$randomPrefix 	= rand(1,999999) . str_shuffle("abcdefghijklmnopqrstuvwxyz") . time();
					$fileTarget		= $uploadPath . $randomPrefix . $fileName;
					
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$fileName: $fileName <i>(" . basename(__FILE__) . ")</i></p>";	
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$fileSize: $fileSize <i>(" . basename(__FILE__) . ")</i></p>";	
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$fileTemp: $fileTemp <i>(" . basename(__FILE__) . ")</i></p>";	
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$fileTarget: $fileTarget <i>(" . basename(__FILE__) . ")</i></p>";	
					
					// Genauere Informationen zum Bild auslesen
					$imageData 		= @getimagesize($fileTemp);
					
					/*
						Die Funktion getimagesize() liefert bei gültigen Bildern ein Array zurück:
						Die Bildbreite in PX [0]
						Die Bildhöhe in PX [1]
						Einen für die HTML-Ausgabe vorbereiteten String für das IMG-Tag
						(width="480" height="532") [3]
						Die Anzahl der Bits pro Kanal ['bits']
						Die Anzahl der Farbkanäle (somit auch das Farbmodell: RGB=3, CMYK=4) ['channels']
						Den echten(!) MIME-Type ['mime']
					*/

					$imageWidth		= $imageData[0];
					$imageHeight	= $imageData[1];
					$imageMimeType	= $imageData['mime'];
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$imageWidth: $imageWidth px <i>(" . basename(__FILE__) . ")</i></p>";	
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$imageHeight: $imageHeight px <i>(" . basename(__FILE__) . ")</i></p>";	
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$imageMimeType: $imageMimeType <i>(" . basename(__FILE__) . ")</i></p>";	

					
					/********** BILD PRÜFEN **********/
					
					// MIME-TYPE prüfen
					// Whitelist mit erlaubten Bildtypen
					// $allowedMimeTypes = array("image/jpg", "image/jpeg", "image/gif", "image/png");

					if( !in_array($imageMimeType, $allowedMimeTypes) ) {
						$errorMessage = "Dies ist kein gültiger Bildtyp!";
						
					// Maximal erlaubte Bildbreite	
					} elseif( $imageWidth > $maxWidth ) {
						$errorMessage = "Die Bildbreite darf maximal $maxWidth Pixel betragen!";
						
					// Maximal erlaubte Bildhöhe	
					} elseif( $imageHeight > $maxHeight ) {
						$errorMessage = "Die Bildhöhe darf maximal $maxHeight Pixel betragen!";
						
					// Maximal erlaubte Dateigröße	
					} elseif( $fileSize > $maxSize ) {
						$errorMessage 	= "Die Dateigröße darf maximal " . round($maxSize/1024, 2) . " kB betragen!";
						
					// Wenn es keine Fehler gab	
					} else {
						$errorMessage 	= NULL;
					}
					
					
					// Abschließende Bildprüfung
					if( !$errorMessage ) {
						// Erfolgsfall
if(DEBUG_F)			echo "<p class='debugImageUpload ok'><b>Line  " . __LINE__ .  "</b>: Die Bildprüfung ergab keine Fehler. <i>(" . basename(__FILE__) . ")</i></p>";	
						
						
						/********** BILD SPEICHERN **********/
						
						// Bild an seinen endgültigen Speicherort verschieben
						if( @move_uploaded_file($fileTemp, $fileTarget) ) {
							// Erfolgsfall
if(DEBUG_F)				echo "<p class='debugImageUpload ok'><b>Line  " . __LINE__ .  "</b>: Das Bild wurde erfolgreich unter '$fileTarget' gespeichert. <i>(" . basename(__FILE__) . ")</i></p>";	
													
						} else {
							// Fehlerfall
if(DEBUG_F)				echo "<p class='debugImageUpload err'><b>Line  " . __LINE__ .  "</b>: FEHLER beim Speichern der Datei auf dem Server! <i>(" . basename(__FILE__) . ")</i></p>";	
							$errorMessage = "FEHLER beim Speichern der Datei auf dem Server!";							
						} // BILD AUF SERVER SPEICHERN ENDE
						
					} // BILDPRÜFUNGEN ENDE
				
					/********** FEHLERMELDUNG UND BILDPFAD ZURÜCKGEBEN **********/
					
					return array( "imageError" => $errorMessage, "imagePath" => $fileTarget );
					
				}


/*************************************************************************************/
?>


























