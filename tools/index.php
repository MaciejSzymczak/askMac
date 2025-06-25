<!DOCTYPE html>
<html lang="en">
<head>
  <title>Mac Tools</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
  <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
  <style>
  .fakeimg {
      height: 200px;
      background: #aaa;
  }
  </style>
  <script>
		String.prototype.lines = function() { return this.split(/\r*\n/); }
		String.prototype.lineCount = function() { return this.lines().length; }
  </script>			
</head>
<body>

<div class="container">
  <div class="row my-4">
    <div class="col">
      <div class="jumbotron">
        <h1>Mac Tools</h1>
		<a href="https://soft.home.pl"><p class="lead">by Mac</p></a>
      </div>
    </div>
  </div>
  
  
<?php
function stringContains(string $haystack, string $needle) {
  return strpos(strtoupper($haystack), strtoupper($needle)) !== false;
}

function strNormalize($name_in)
{
    return trim(str_replace('  ',' ',str_replace('  ',' ',str_replace('  ',' ',str_replace('  ',' ',$name_in)))));
}

function strCamelCase($name_in)
{
    $a_names = explode(' ', $name_in); 
    foreach ($a_names as $name) {
        $a_normalized[] = ucfirst(strtolower($name)); 
    }    
    $string = implode(' ', $a_normalized);
    return $string;
}


function addButton($hintText, $buttonText, $buttonAPIName) {
    echo '
<div class="col-md-2">
  <div class="card mb-1 box-shadow">
	<div class="card-body flex-fill">
	  <p class="card-text" style="height: 35px">'.$hintText.'</p>
	  <div class="d-flex justify-content-between align-items-center">
		<div class="btn-group">
		  <button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, \''.$buttonAPIName.'\');">'.$buttonText.'</button>
		</div>
	  </div>
	</div>
  </div>
</div>';
}

?>
    
	
<?php

  if(!isset($_POST['command']) || !stringContains('COMMAND_NORMALIZE,COMMAND_PREFIX_PARAMS,COMMAND_CSV,COMMAND_ERASE_PARAMS,COMMAND_REPLACE,COMMAND_MAXLEN,COMMAND_BASE64_PARAMS,COMMAND_URLENCODEDECODE', $_POST['command'])){
?>
      <div class="album py-3 bg-light">
        <div class="container">

          <div class="row">		  		  
			<?php addButton('Beautify'                              ,'Beautify'    ,'COMMAND_NORMALIZE'); ?>
			<?php addButton('Duplicates: Show'                      ,'Duplicates'   ,'COMMAND_DUPLICATES'); ?>
			<?php addButton('Duplicates: Remove'                    ,'Unique list'  ,'COMMAND_UNIQUE_LIST'); ?>
			<?php addButton('Fuzzy search and check for duplicates' ,'Similar texts','COMMAND_SIMILAR'); ?>
			<?php addButton('SQL format/ Surround with text'        ,'Format'     ,'COMMAND_PREFIX_PARAMS'); ?>
			<?php addButton('Extract columns'                       ,'Extract columns','COMMAND_CSV'); ?>
			<?php addButton('Extract lines'                         ,'Extract lines','COMMAND_ERASE_PARAMS'); ?>
			<?php addButton('Search and replace'                    ,'Replace'      ,'COMMAND_REPLACE'); ?>
			<?php addButton('Check the length'                      ,'Max Len'      ,'COMMAND_MAXLEN'); ?>
			<?php addButton('Decode and encode text'                ,'Base64'       ,'COMMAND_BASE64_PARAMS'); ?>
			<?php addButton('Decode and encode text'                ,'URL'          ,'COMMAND_URLENCODEDECODE'); ?>
			<?php addButton('UML Editor'                            ,'UML Editor'   ,'COMMAND_UML'); ?>
			<!--?php addButton('Coming soon','Intersect','COMMAND_INTERSECT'); ?-->
			<!--?php addButton('Coming soon','Difference','COMMAND_DIFFERENCE'); ?-->

          </div>
        </div>
      </div>
<?php
  }
?>
	  
	  



<?php
/*****************************************************/
/***************************** NORMALIZE *************/
/*****************************************************/
  if(isset($_POST['command']) && stringContains('COMMAND_NORMALIZE', $_POST['command'])){
?>

<div class="container" style="margin-top:30px">
	<div class="row">
		<div class="col-sm-12 text-center">
			<div class="d-flex justify-content-center">

				<div class="card">
				  <div class="card-header">
					Beautify
				  </div>
				  <div class="card-body flex-fill">
					<h5 class="card-title"></h5>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_NORMALIZE_RUN');">Remove redundant spaces and empty lines</button>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_CAMEL_RUN');">Camel Case</button>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_UPPER_RUN');">UPPER CASE</button>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_LOWER_RUN');">lower case</button>
				  </div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
  }
?>


<?php

/*****************************************************/
/***************************** PREFIX ****************/
/*****************************************************/



  if(isset($_POST['command']) && stringContains('COMMAND_PREFIX_PARAMS', $_POST['command'])){
?>

<div class="container" style="margin-top:30px">
	<div class="row">
		<div class="col-sm-12 text-center">
			<div class="d-flex justify-content-center">

				<div class="card">
				  <div class="card-header">
					SQL Format / Surround lines with text
				  </div>
				  <div class="card-body flex-fill">
					<h5 class="card-title"></h5>

					  <div class="mb-3">
						  <label>
							<input type="checkbox" id="chbsqlformat" onchange="updateSqlFormat()" <?php echo ($_POST['hsqlformat']=='NO') ? "" : "checked";?> > SQL Format
						  </label>					  
					  </div>	
					  <script>
						function updateSqlFormat() {
						  const checkbox = document.getElementById("chbsqlformat");
						  const textField = document.getElementById("sqlformat");

						  textField.value = checkbox.checked ? "YES" : "NO";
						  
						  const divPrefix = document.getElementById("divPrefix");
						  const divPostfix = document.getElementById("divPostfix");
						  
						  divPrefix.style.display = checkbox.checked ? "none" : "";
						  divPostfix.style.display = checkbox.checked ? "none" : "";
						}
						window.onload = updateSqlFormat;
					  </script>

					  <div class="mb-3">
						<input type="hidden" class="form-control" name="sqlformat" id="sqlformat" value="<?php echo ($_POST['hsqlformat']=='') ? ",'" : $_POST['hsqlformat'];?>">
					  </div>	

					  <div class="mb-3" id="divPrefix">
						<label for="prefix" class="form-label">Prefix</label>
						<input type="text" class="form-control" id="prefix" value="<?php echo ($_POST['hprefix']=='') ? ",'" : $_POST['hprefix'];?>">
					  </div>	
					  
					  <div class="mb-3"  id="divPostfix">
						<label for="postfix" class="form-label">Postfix</label>
						<input type="text" class="form-control" id="postfix" value="<?php echo ($_POST['hpostfix']=='') ? "'" : $_POST['hpostfix'];?>">
					  </div>										
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_PREFIX_RUN');">Format</button>
				  </div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
  }
?>


<?php
/*****************************************************/
/***************************** COMMAND_CSV ***********/
/*****************************************************/



  if(isset($_POST['command']) && stringContains('COMMAND_CSV', $_POST['command'])){
?>

<div class="container" style="margin-top:30px">
	<div class="row">
		<div class="col-sm-12 text-center">
			<div class="d-flex justify-content-center">

				<div class="card">
				  <div class="card-header">
					Extract columns
				  </div>
				  <div class="card-body flex-fill">
					<h5 class="card-title"></h5>
					  <div class="mb-3">
						<label for="separator" class="form-label">Separator</label>
						<input type="text" class="form-control" id="separator" value="<?php echo ($_POST['hseparator']=='') ? "," : $_POST['hseparator'];?>">
					  </div>	
					  
					  <div class="mb-3">
						<label for="stringNo" class="form-label">String number</label>
						<input type="text" class="form-control" id="stringNo" value="<?php echo ($_POST['hstringNo']=='') ? "0" : $_POST['hstringNo'];?>">
					  </div>										
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_CSV_RUN');">Extract string</button>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_CSV_COUNT');">Count strings</button>
				  </div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
  }
?>


<?php
/*****************************************************/
/***************************** ERASE *****************/
/*****************************************************/

  if(isset($_POST['command']) && strpos('COMMAND_ERASE_PARAMS', $_POST['command']) !== false){
?>

<div class="container" style="margin-top:30px">
	<div class="row">
		<div class="col-sm-12 text-center">
			<div class="d-flex justify-content-center">

				<div class="card">
				  <div class="card-header">
					Extract lines
				  </div>
				  <div class="card-body flex-fill">
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_ERASE_WITH_RUN');">Erase lines with texts</button>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_ERASE_WITHOUT_RUN');">Erase lines without texts</button>				  
					<h5 class="card-title"></h5>
					  <div class="mb-3">
						<label for="text1" class="form-label">Texts</label>
						<input type="text" class="form-control" id="text1" value="<?php echo ($_POST['htext1']=='') ? "" : $_POST['htext1'];?>">
					  </div>	
					  
					  <div class="mb-3">
						<input type="text" class="form-control" id="text2" value="<?php echo ($_POST['htext2']=='') ? "" : $_POST['htext2'];?>">
					  </div>										

					  <div class="mb-3">
						<input type="text" class="form-control" id="text3" value="<?php echo ($_POST['htext3']=='') ? "" : $_POST['htext3'];?>">
					  </div>										
				  </div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
  }
?>

<?php
/*****************************************************/
/***************************** REPLACE ***************/
/*****************************************************/

  if(isset($_POST['command']) && strpos('COMMAND_REPLACE', $_POST['command']) !== false){
?>

<div class="container" style="margin-top:30px">
	<div class="row">
		<div class="col-sm-12 text-center">
			<div class="d-flex justify-content-center">

				<div class="card">
				  <div class="card-header">
					Search and replace
				  </div>
				  <div class="card-body flex-fill">
					<h5 class="card-title"></h5>
					  <div class="mb-3">
						<label for="searchFor" class="form-label">Search for</label>
						<input type="text" class="form-control" id="searchFor" value="<?php echo ($_POST['hsearchFor']=='') ? "" : $_POST['hsearchFor'];?>">
					  </div>	

					  <div class="mb-3">
						<label for="replaceWith" class="form-label">Replace with</label>
						<input type="text" class="form-control" id="replaceWith" value="<?php echo ($_POST['hreplaceWith']=='') ? "" : $_POST['hreplaceWith'];?>">
					  </div>	
					  
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_REPLACE_RUN');">Replace</button>
				  </div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
  }
?>



<?php
/*****************************************************/
/***************************** MAXLEN *************/
/*****************************************************/

  if(isset($_POST['command']) && strpos('COMMAND_MAXLEN', $_POST['command']) !== false){
?>

<div class="container" style="margin-top:30px">
	<div class="row">
		<div class="col-sm-12 text-center">
			<div class="d-flex justify-content-center">

				<div class="card">
				  <div class="card-header">
					Check the length
				  </div>
				  <div class="card-body flex-fill">
					<h5 class="card-title"></h5>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_LEN_RUN');">Show the lengths</button>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_MAXLEN_RUN');">Truncate strings longer than N characters</button>
					  <div class="mb-3">
						<label for="maxLen" class="form-label">Max length</label>
						<input type="text" class="form-control" id="maxLen" value="<?php echo ($_POST['hmaxLen']=='') ? "80" : $_POST['hmaxLen'];?>">
					  </div>	
				  </div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
  }
?>

<?php
/*****************************************************/
/***************************** BASE64 *****************/
/*****************************************************/

  if(isset($_POST['command']) && strpos('COMMAND_BASE64_PARAMS', $_POST['command']) !== false){
?>

<div class="container" style="margin-top:30px">
	<div class="row">
		<div class="col-sm-12 text-center">
			<div class="d-flex justify-content-center">

				<div class="card">
				  <div class="card-header">
					Decode and encode text
				  </div>
				  <div class="card-body flex-fill">
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_BASE64_ENCODE_RUN');">Encode</button>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_BASE64_DECODE_RUN');">Decode</button>				  
					<h5 class="card-title"></h5>										
				  </div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
  }
?>

<?php
/*****************************************************/
/***************************** URLENCODE *****************/
/*****************************************************/

  if(isset($_POST['command']) && strpos('COMMAND_URLENCODEDECODE', $_POST['command']) !== false){
?>

<div class="container" style="margin-top:30px">
	<div class="row">
		<div class="col-sm-12 text-center">
			<div class="d-flex justify-content-center">

				<div class="card">
				  <div class="card-header">
					URL Encode
				  </div>
				  <div class="card-body flex-fill">
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_URL_ENCODE_RUN');">Encode</button>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_URL_RAW_ENCODE_RUN');">Raw Encode</button>
					<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_URL_DECODE_RUN');">Decode</button>				  
					<h5 class="card-title"></h5>										
				  </div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
  }
?>



<div class="container" style="margin-top:30px">
  <div class="row">
    <div class="col-sm-6 text-center">
		<script>
		  function executeCommand(currentButton, e) {
		    currentButton.disabled = true;
			//currentButton.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';
			currentButton.innerHTML = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Loading...';
			
			if (e=='COMMAND_COPY') {
				document.getElementById("origText").value=document.getElementById("outputText").value;
			}
			if (e=='COMMAND_PREFIX_RUN') {
				document.getElementById("hsqlformat").value=document.getElementById("sqlformat").value;
				document.getElementById("hprefix").value=document.getElementById("prefix").value;
				document.getElementById("hpostfix").value=document.getElementById("postfix").value;
			}
			if (e=='COMMAND_CSV_RUN') {
				document.getElementById("hseparator").value=document.getElementById("separator").value;
				document.getElementById("hstringNo").value=document.getElementById("stringNo").value;
			}
			if (e=='COMMAND_CSV_COUNT') {
				document.getElementById("hseparator").value=document.getElementById("separator").value;
				document.getElementById("hstringNo").value=document.getElementById("stringNo").value;
			}
			if (e=='COMMAND_ERASE_WITH_RUN') {
				document.getElementById("htext1").value=document.getElementById("text1").value;
				document.getElementById("htext2").value=document.getElementById("text2").value;
				document.getElementById("htext3").value=document.getElementById("text3").value;
			}
			if (e=='COMMAND_ERASE_WITHOUT_RUN') {
				document.getElementById("htext1").value=document.getElementById("text1").value;
				document.getElementById("htext2").value=document.getElementById("text2").value;
				document.getElementById("htext3").value=document.getElementById("text3").value;
			}
			if (e=='COMMAND_REPLACE_RUN') {
				document.getElementById("hsearchFor").value=document.getElementById("searchFor").value;
				document.getElementById("hreplaceWith").value=document.getElementById("replaceWith").value;
			}
			if (e=='COMMAND_LEN_RUN') {
				document.getElementById("hmaxLen").value=document.getElementById("maxLen").value;
			}
			if (e=='COMMAND_MAXLEN_RUN') {
				document.getElementById("hmaxLen").value=document.getElementById("maxLen").value;
			}
			document.getElementById("command").value=e;
			document.getElementById("action").submit();
		  }
 
		</script>
		<form id="action" action="" method="POST">
			<input type="hidden" id="command" name="command" value=""/>
			<input type="hidden" id="hsqlformat" name="hsqlformat" value="<?php echo $_POST['hsqlformat'];?>"/>
			<input type="hidden" id="hprefix" name="hprefix" value="<?php echo $_POST['hprefix'];?>"/>
			<input type="hidden" id="hpostfix" name="hpostfix" value="<?php echo $_POST['hpostfix'];?>"/>
			<input type="hidden" id="hseparator" name="hseparator" value="<?php echo $_POST['hseparator'];?>"/>
			<input type="hidden" id="hstringNo" name="hstringNo" value="<?php echo $_POST['hstringNo'];?>"/>
			<input type="hidden" id="htext1" name="htext1" value="<?php echo $_POST['htext1'];?>"/>
			<input type="hidden" id="htext2" name="htext2" value="<?php echo $_POST['htext2'];?>"/>
			<input type="hidden" id="htext3" name="htext3" value="<?php echo $_POST['htext3'];?>"/>
			<input type="hidden" id="hsearchFor" name="hsearchFor" value="<?php echo $_POST['hsearchFor'];?>"/>
			<input type="hidden" id="hreplaceWith" name="hreplaceWith" value="<?php echo $_POST['hreplaceWith'];?>"/>
			<input type="hidden" id="hmaxLen" name="hmaxLen" value="<?php echo $_POST['hmaxLen'];?>"/>

			<div data-mdb-input-init class="form-outline w-100">
				<label class="form-label" for="origText">Source</label>
				<textarea class="form-control" id="origText" name="origText" rows="15"><?php echo $_POST['origText'];?></textarea>
				<p>Lines:
					<script>
						document.write(document.getElementById("origText").value.lineCount());
					</script>			
				</p>
			</div>
		</form>  
    </div>

<?php

  if(isset($_POST['command']) 
	&& ($_POST['command']=='COMMAND_UML')) {
	echo "<script> location.href='https://soft.home.pl/tools/plantuml.php'; </script>";
  }

  if(isset($_POST['command']) 
	&& ($_POST['command']=='COMMAND_UNIQUE_LIST'
	 || $_POST['command']=='COMMAND_DUPLICATES'
	 || $_POST['command']=='COMMAND_SIMILAR'
	 || $_POST['command']=='COMMAND_PREFIX_RUN'
	 || $_POST['command']=='COMMAND_CSV_RUN'
	 || $_POST['command']=='COMMAND_CSV_COUNT'
	 || $_POST['command']=='COMMAND_ERASE_WITH_RUN'
	 || $_POST['command']=='COMMAND_ERASE_WITHOUT_RUN'
	 || $_POST['command']=='COMMAND_REPLACE'
	 || $_POST['command']=='COMMAND_REPLACE_RUN'
	 || $_POST['command']=='COMMAND_NORMALIZE_RUN' 
	 || $_POST['command']=='COMMAND_UPPER_RUN'
	 || $_POST['command']=='COMMAND_LOWER_RUN'
	 || $_POST['command']=='COMMAND_CAMEL_RUN'
	 || $_POST['command']=='COMMAND_LEN_RUN'
	 || $_POST['command']=='COMMAND_MAXLEN_RUN'
	 || $_POST['command']=='COMMAND_BASE64_ENCODE_RUN'
	 || $_POST['command']=='COMMAND_BASE64_DECODE_RUN'
	 || $_POST['command']=='COMMAND_URL_ENCODE_RUN'
	 || $_POST['command']=='COMMAND_URL_RAW_ENCODE_RUN'
	 || $_POST['command']=='COMMAND_URL_DECODE_RUN'
	 )
	){
?>

    <div class="col-sm-6 text-center">
		<div data-mdb-input-init class="form-outline w-100">
			<label class="form-label" for="outputText">Result</label>
			<button type="button" class="btn btn-outline-secondary" onclick="executeCommand(this, 'COMMAND_COPY');">Copy Result into Source</button>
			<textarea class="form-control" id="outputText" name="outputText" rows="15"><?php	
			$input = isset($_POST['origText'])?$_POST['origText']:"";
			if (strlen($input)!=0) {
			
				if ($_POST['command']=='COMMAND_UNIQUE_LIST') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					$ids = array_unique ($ids, SORT_STRING );
					array_multisort ($ids, SORT_ASC , SORT_STRING );
					print implode(PHP_EOL, $ids);
				}
				
				if ($_POST['command']=='COMMAND_DUPLICATES') {
					$res = array_count_values(explode("\n", str_replace("\r", "", $input)));
					
					$insideLoop = false;
					foreach($res as $key => $value) {
					  if ($value>1) {
						if ($insideLoop==false) {
						print "[Text] => The number of duplicates" . PHP_EOL;
						}
						print "[" . $key . "] => " . $value . PHP_EOL;
						$insideLoop = true;
					  }
					}
					
					$insideLoop = false;
					foreach($res as $key => $value) {
					  if ($value==1) {
						if ($insideLoop==false) {
						print "" . PHP_EOL;
						print "[Text] => No duplicates" . PHP_EOL;
						}
						print "[" . $key . "] => " . $value . PHP_EOL;
						$insideLoop = true;
					  }
					}
					
				}	

				if ($_POST['command']=='COMMAND_SIMILAR') {
				
					$res = array("X" => "Y");
					$ids1 = explode("\n", str_replace("\r", "", $input));	
					$ids2 = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids1 as $key1=>$x1) {
						foreach ($ids2 as $key2=>$x2) {
						  if ($key2 > $key1 && ($x1!='' || $x2!='')) {
							similar_text($x1,$x2,$percent); 
							$percent=round($percent);
							if ($percent>50) {
								$val = '['.$x1.'] and ['.$x2.']';
								$res += [$val => $percent];
							}
						  }
						}
					}
					
					arsort($res, SORT_NUMERIC);
					
					//display res
					$insideLoop = false;
					foreach($res as $key => $value) {
					  if ($value>1) {
						if ($insideLoop==false) {
						print "[Text] => Similarity [%]" . PHP_EOL;
						}
						print  $key . " => " . $value . PHP_EOL;
						$insideLoop = true;
					  }
					}
					if ($insideLoop==false) {
					print "No similar texts". PHP_EOL;
					}						
					
				}	

				if ($_POST['command']=='COMMAND_PREFIX_RUN') {
					$ids2 = explode("\n", str_replace("\r", "", $input));
					//Remove empty values
					$ids = array_filter($ids2, function($val) {
						return $val !== '';
					});
					
					if ($_POST['hsqlformat']=='YES') {
					    echo "Id in (";

						// Step 2: Wrap each element in single quotes
						$quoted = array_map(function($item) {
							return "'" . trim($item) . "'";
						}, $ids);

						// Step 3: Join with pipe separator
						$result = implode(",", $quoted);

						echo $result; // Output: 'X'|'Y'|'Z'
						
						echo ")";
					} else {
						foreach ($ids as $x) {											
							if (trim($x) === '') {
								//ignore blanks						
							} else {
								echo $_POST['hprefix']."$x".$_POST['hpostfix'].PHP_EOL;
							}				
						}
					}					
				}	

				if ($_POST['command']=='COMMAND_CSV_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
					  $arr = explode($_POST['hseparator'], "$x");
					  echo $arr[$_POST['hstringNo']].PHP_EOL;
					}
				}	

				if ($_POST['command']=='COMMAND_CSV_COUNT') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
					  $arr = explode($_POST['hseparator'], "$x");
					  echo "$x".":".count($arr).PHP_EOL;
					}
				}	

				if ($_POST['command']=='COMMAND_ERASE_WITH_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
					  $c1 = $_POST['htext1']==""?false:stringContains("$x", $_POST['htext1']);
					  $c2 = $_POST['htext2']==""?false:stringContains("$x", $_POST['htext2']);
					  $c3 = $_POST['htext3']==""?false:stringContains("$x", $_POST['htext3']);						
					  if ( $c1 || $c2 || $c3 ) {
						/* ignore line */
					  } else {
						echo "$x".PHP_EOL;
					  }
					}
				}	

				if ($_POST['command']=='COMMAND_ERASE_WITHOUT_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
					  $c1 = $_POST['htext1']==""?false:stringContains("$x", $_POST['htext1']);
					  $c2 = $_POST['htext2']==""?false:stringContains("$x", $_POST['htext2']);
					  $c3 = $_POST['htext3']==""?false:stringContains("$x", $_POST['htext3']);						
					  if ( $c1 || $c2 || $c3 ) {
						echo "$x".PHP_EOL;
					  } else {
						/* ignore line */
					  }
					}
				}	

				if ($_POST['command']=='COMMAND_REPLACE_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
						echo str_replace($_POST['hsearchFor'], $_POST['hreplaceWith'], $x).PHP_EOL;
					}
				}	

				if ($_POST['command']=='COMMAND_NORMALIZE_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
					  $normalized = strNormalize($x);
					  if ($normalized!='')
						echo $normalized.PHP_EOL;
					}
				}	

				if ($_POST['command']=='COMMAND_UPPER_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
						echo strtoupper($x).PHP_EOL;
					}
				}	

				if ($_POST['command']=='COMMAND_LOWER_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
						echo strtolower($x).PHP_EOL;
					}
				}	

				if ($_POST['command']=='COMMAND_CAMEL_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
					  $normalized = strCamelCase($x);
					  if ($normalized!='')
						echo $normalized.PHP_EOL;
					}
				}	


				if ($_POST['command']=='COMMAND_LEN_RUN') {
					$res = array_count_values(explode("\n", str_replace("\r", "", $input)));

					foreach($res as $key => $value) {
						$res [$key] = strlen($key);
					}
					arsort($res, SORT_NUMERIC);

					foreach($res as $key => $value) {
						print "[" . $key . "] => " . $value . ($value > $_POST['hmaxLen']?" !":"") . PHP_EOL;
					}

				}	

				if ($_POST['command']=='COMMAND_MAXLEN_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
						echo substr($x,0,$_POST['hmaxLen']).PHP_EOL;
					}
				}	

				if ($_POST['command']=='COMMAND_BASE64_ENCODE_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
						echo base64_encode("$x").PHP_EOL;
					}
				}	

				if ($_POST['command']=='COMMAND_BASE64_DECODE_RUN') {
					$ids = explode("\n", str_replace("\r", "", $input));	
					foreach ($ids as $x) {
						echo base64_decode("$x").PHP_EOL;
					}
				}	

				if ($_POST['command']=='COMMAND_URL_ENCODE_RUN') {
					//$ids = explode("\n", str_replace("\r", "", $input));	
					//foreach ($ids as $x) {
					//	echo urlencode("$x").PHP_EOL;
					//}
					echo urlencode("$input").PHP_EOL;
				}	

				if ($_POST['command']=='COMMAND_URL_RAW_ENCODE_RUN') {
					//$ids = explode("\n", str_replace("\r", "", $input));	
					//foreach ($ids as $x) {
					//	echo rawurlencode("$x").PHP_EOL;
					//}
					echo rawurlencode("$input").PHP_EOL;
				}	

				if ($_POST['command']=='COMMAND_URL_DECODE_RUN') {
					//$ids = explode("\n", str_replace("\r", "", $input));	
					//foreach ($ids as $x) {
					//	echo urldecode("$x").PHP_EOL;
					//}
					echo urldecode("$input").PHP_EOL;
				}	


			}?></textarea>
			<p>Lines:
				<script>
					document.write(document.getElementById("outputText").value.lineCount());
				</script>			
			</p>
		</div>
    </div>

<?php
  }
?>

  </div>
</div>

<div class="jumbotron text-center" style="margin-bottom:0">
  <p>This page is not collecting any data</p>
</div>

</body>
</html>

