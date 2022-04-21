<?php

class CSVImport{

	private $directory;
	private $separation;
	private $includes;
	private $dataGroup;
	private $setTitles;

	private $fileHandle;

	public $csvData;

	private $errors = [];

	private $separaters = [',',' ','|',';','\t'];

	function __construct(
		$directory,
		$separation,
		$includes,
		$dataGroup,
		$setTitles
	) {
		$this->directory = $directory;
		$this->separation = $separation ? $separation : ',';
		$this->includes = $includes ? $includes : false;
		$this->dataGroup = $dataGroup ? $dataGroup : false;
		$this->setTitles = $setTitles ? $setTitles : false;
		$this->init();
	}

	private function init(){

		set_error_handler(
			array($this, 'csvErrors')
		);

		if(
			!$this->directory
		){
			return $this->errors['Code - 0'] = 'No File Link';
		}

		$this->setSeparaters();

		$this->readFile();

	}

	private function setSeparaters(){

		if(
			!in_array( $this->separation, $this->separaters )
		) {
    	return $this->errors['Code - 1'] = 'Incorrect Separation';
		}

	}

	private function openFile(){

		$this->fileHandle = fopen($this->directory, "r");

	}

	private function closeFile(){

		if(!$this->fileHandle) return;

		fclose(
			$this->fileHandle
		);

	}

	private function readFile(){

		 $this->openFile();

		 $this->closeFile();

		 if (
			 !file_exists($this->directory)
		 ) {
	     $this->errors['Code - 2'] = 'File Not Found';
	   }

		 if (
		 	 !$this->fileHandle
		 	) {
			 $this->errors['Code - 3'] = 'File Read Error';
		 }

		 return $this->errors ? $this->errors : $this->setFileData();

	 }

	 private function setFileData(){

		 $this->openFile();

		 $dataSet = [];

		 while (
			 $data = fgetcsv($this->fileHandle, false, $this->separation)
		 ) {
			 $dataSet[] = $data;
		 }

		 $this->closeFile();

		 if (
		 	 !$dataSet
		 	) {
			 $this->errors['Code - 4'] = 'No Data Set';
		 }

		 return $this->errors ? $this->errors : $this->setData( $dataSet );

	 }

	 private function setData( $dataSet ){

		 $dataArray = [];
		 $dataTitles = [];

		 for (
			 $key = 0; $key < count($dataSet); $key++
		 ) {

		  foreach (
		 	  $dataSet[$key] as $keyInner => $valueInner
		  ) {

		    // Set Key Titles
		    if(
		      $key == 0 &&
		    	$this->setTitles !== false
		    ){
		    	$titleArray[] = $this->setTitle($keyInner, $valueInner);
		    	$titleArray ? $dataTitles = $titleArray : $this->errors['Code - 5'] = 'Data Title Error';
				}

		  	// Set Values
		   	if(
		   		$this->dataGroup
		   	){
		   		$dataTitles[$keyInner] ? $dataArray[$dataTitles[$keyInner]][] = $valueInner : null;
		   		continue;
		   	}

		   	if(
		   		$dataTitles[$keyInner]
		   	){
		   		$dataArray[$key][$dataTitles[$keyInner]] = $valueInner;
		   	}

		  	if(
		  		count($dataTitles) < 1
		  	){
		  		$dataArray[$key][] = $valueInner;
		  	}

		 	}

		}

		return !$dataArray ? $this->errors['Code - 6'] = 'Data Error' : $this->csvData = $dataArray;

	 }

	 private function setTitle( $key, $value){

		 if(
			$this->includes
		 ){

	     if(
	     	in_array( $value, $this->includes )
	     ) {
				 
	     	return $dataTitles[$key] = $value;
				 
	     }

		 } else {
				 
			 return $dataTitles[$key] = $value;
				 
		 }

   }

   private function csvErrors() {

     echo '<strong>ERROR WITH IMPORT!</strong><br />';

     foreach ($this->errors as $key => $value) {
     	echo "{$key} - {$value} \n";
     }

   }

   public function getData(){

    return $this->errors ? trigger_error('csvErrors') : $this->csvData;

   }

}

 $CSVData = new CSVImport(
	'/var/www/vhosts/jaybeeplant.co.uk/httpdocs/bedrock/web/app/themes/MO-Wordpress/assets/uploadCSV.csv', // directory
	',', // separaters
	["id", "description", "title", "object_type", "name"], // includes
	false, // group data
	true // set titles
);

$csvItems = $CSVData->getData();

