<?php
	abstract class Book{
		private $isbnNumber;
		private $title;
		private $author;
		private $basicPrice;

		public function __construct(
			int $isbnNumber, 
			string $title, 
			string $author, 
			float $basicPrice
		) {
			$this->isbnNumber = $isbnNumber;
			$this->title = $title;
			$this->author = $author;
			$this->basicPrice = $basicPrice;
		}

		//Getters
		public function getIsbnNumber(): int{
			return $this->isbnNumber;
		}
		public function getTitle(): string{
			return $this->title;
		}
		public function getAuthor(): string{
			return $this->author;
		}
		public function getBasicPrice(): float{
			return $this->basicPrice;
		}

		//Setters
		public function setIsbnNumber(int $isbnNumber){
			$this->isbnNumber = $isbnNumber;
		}
		public function setTitle(string $title){
			$this->title = $title;
		}
		public function setAuthor(int $author){
			$this->author = $author;
		}
		public function setBasicPrice(int $basicPrice){
			$this->basicPrice = $basicPrice;
		}

		abstract public function calculatePrice();
	}

	//OnlineBook class definition
	class OnlineBook extends Book{
		private $fileSize;
		private $downloadUrl;

		public function __construct(
			int $isbnNumber, 
			string $title, 
			string $author, 
			float $basicPrice,
			float $fileSize,
			string $downloadUrl = 'https://www.martin-loves-simona.com/download'
		) {
			parent::__construct($isbnNumber, $title, $author, $basicPrice);

			$this->fileSize = $fileSize;
			$this->downloadUrl = $downloadUrl;
		}

		//Getters
		public function getFileSize(): float{
			return $this->fileSize;
		}
		public function getDownloadUrl(): string{
			return $this->downloadUrl;
		}

		//Setters
		public function setFileSize(float $fileSize){
			$this->fileSize = $fileSize;
		}
		public function setDownloadUrl(string $downloadUrl){
			$this->downloadUrl = $downloadUrl;
		}

		public function calculatePrice(){
			if($this->fileSize > (float)20){
				return $this->getBasicPrice() + $this->getBasicPrice() * 0.2;
			}
			return $this->getBasicPrice();
		}
	}

	//PrintedBook class definition
	class PrintedBook extends Book{
		private $weight;
		private $inStock;

		public function __construct(
			int $isbnNumber, 
			string $title, 
			string $author, 
			float $basicPrice,
			float $weight,
			bool $inStock = true
		) {
			parent::__construct($isbnNumber, $title, $author, $basicPrice);

			$this->weight = $weight;
			$this->inStock = $inStock;
		}

		//Getters
		public function getWeight(): float{
			return $this->weight;
		}
		public function getInStock(): bool{
			return $this->inStock;
		}

		//Setters
		public function setWeight(float $weight){
			$this->weight = $weight;
		}
		public function setInStock(float $inStock){
			$this->inStock = $inStock;
		}

		public function calculatePrice(){
			if($this->weight > 0.7){
				return $this->getBasicPrice() + $this->getBasicPrice() * 0.15;
			}
			return $this->getBasicPrice();
		} 
	}

	function printService(Book $b){
		return
			$b->getIsbnNumber() . ': ' .
			$b->getTitle() . ', ' .
			$b->getAuthor() . ', ' .
			$b->calculatePrice() . '<br />';
	}

	function priceCompareService(Book $a, Book $b){
		if($a->calculatePrice() < $b->calculatePrice()){
			return printService($a);
		}
		return printService($b);
	}

	$onlineBook1 = new OnlineBook(394830, 'The Red Book', 'Carl Gustav Jung', 24.99, 25);
	$onlineBook2 = new OnlineBook(948503, 'To Have and to Have Not', 'Ernest Hemingway', 22.99, 15);
	$onlineBook3 = new OnlineBook(493432, 'Man\'s Search for Meaning', 'Viktor Frankl', 23.49, 20);

	$printedBook1 = new PrintedBook(3212341, 'For Whom the Bells Toll', 'Ernest Hemingway', 26.99, 1);
	$printedBook2 = new PrintedBook(919183, 'White Dawns', 'Kocho Racin', 22.99, 0.5);

	$books = [$onlineBook1, $onlineBook2, $onlineBook3, $printedBook1, $printedBook2];

	foreach ($books as $book) {
		echo printService($book);
	}
	echo '<br />';
	echo priceCompareService($onlineBook1, $printedBook2);
	echo priceCompareService($onlineBook2, $printedBook1);
	echo priceCompareService($onlineBook1, $onlineBook3);
	echo '<br />';
	//////////////////////////////////////////////////////////////
	
	//Task 2 start

	//Employee class definition
	class Employee{
		private $name;
		private $surname;
		private $sex;
		private $mbr;

		public function __construct(
			string $name,
			string $surname,
			string $sex,
			string $mbr
		) {
			$this->name = $name;
			$this->surname = $surname;
			$this->sex = $sex;
			$this->mbr = $mbr;
		}

		public function getSex():string{
			return $this->sex;
		}
	}

	//Library class definition
	class Library{
		private $name;
		private $location;
		private $booksList;
		private $employees;

		private function validateLibrary(array $books, array $employees){
			if(empty($books) || empty($employees)){
				return false;
			}

			$flag1 = false;
			$flag2 = false;

			foreach ($books as $elem) {
				if($elem instanceof Book){
					$flag1 = true;
					break;
				}
			}

			foreach ($employees as $elem) {
				if($elem instanceof Employee){
					$flag2 = true;
					break;
				}
			}

			return $flag1 && $flag2;
		}

		public function __construct(
			string $name,
			string $location,
			array $booksList,
			array $employees
		) {
			if (!($this->validateLibrary($booksList, $employees))) {
				throw new Exception('There has to be an employee and a book so that ' . $name . ' can be opened<br />');
			}

			$this->name = $name;
			$this->location = $location;

			foreach ($booksList as $elem) {
				if ($elem instanceof Book){
					$this->booksList[] = $elem;
				}
			}
			foreach ($employees as $elem) {
				if ($elem instanceof Employee){
					$this->employees[] = $elem;
				}
			}
		}

		public function getName():string{
			return $this->name;
		}

		public function mostExpensiveBook(){
			$price = 0;
			$name = '';
			foreach ($this->booksList as $book) {
				if($book->calculatePrice() > $price){
					$price = $book->calculatePrice();
					$name = $book->getTitle();
				}
			}
			return $name;
		}

		public function leastExpensiveBook(){
			$price = $this->booksList[0]->calculatePrice();
			$name = $this->booksList[0]->getTitle();

			foreach ($this->booksList as $book) {
				if($book->calculatePrice() < $price){
					$price = $book->calculatePrice();
					$name = $book->getTitle();
				}
			}
			return $name;
		}

		public function totalValueOfBooks(){
			$total = 0;
			foreach ($this->booksList as $book) {
				$total += $book->calculatePrice();
			}
			return $total;
		}

		public function hasMaleAndFemaleEmployee(){
			$male = false;
			$female = false;
			foreach ($this->employees as $employee) {
				if($employee->getSex() == 'male'){
					$male = true;
					continue;
				}
				if($employee->getSex() == 'female'){
					$female = true;
				}
			}
			return $male && $female;
		}
	}

	function bobzanLibrary(array $libraries){
		if(sizeof($libraries) == 0){
			return 'There\'s no such library';
		}
		$libs = [];
		foreach ($libraries as $library) {
			if($library->hasMaleAndFemaleEmployee()){
				$libs[] = $library;
			}
		}

		if (sizeof($libs) == 0) {
			return 'There\'s no such library';
		}
		if(sizeof($libs) == 1){
			return $libs[0]->getName();
		}

		$max = 0;
		$libName = '';
		foreach ($libs as $lib) {
			if($max < $lib->totalValueOfBooks()){
				$max = $lib->totalValueOfBooks();
				$libName = $lib->getName();
			}
		}
		return $libName;
	}

	$employee1 = new Employee('mIme1', 'mPrezime1', 'male', '3498374');
	$employee2 = new Employee('mIme2', 'mPrezime2', 'male', '5445674');
	$employee3 = new Employee('zIme3', 'zPrezime3', 'female', '8498374');

	try{
		$library1 = new Library('Bib1', 'mesto1', ['nesto', 5, $onlineBook1, $printedBook1], [7, 'nekoj', $employee1, $employee3]);
		$library2 = new Library('Bib2', 'mesto2', [$onlineBook2, $printedBook2], [$employee1, $employee2]);
		// $library3 = new Library('Bib3', 'mesto3', [$onlineBook2, $printedBook2], []);
		// $library4 = new Library('Bib4', 'mesto4', [], [$employee1]);
		// $library5 = new Library('Bib5', 'mesto5', [5, 'a'], [$employee1, $employee2]);
		// $library6 = new Library('Bib6', 'mesto6', [$onlineBook2, $printedBook2], ['0', 0]);
		$library7 = new Library('Bib7', 'mesto7', [$onlineBook2, $printedBook2, $onlineBook3, $printedBook1], [$employee1, $employee3]);
	} catch(Exception $e){
		echo $e->getMessage();
	}
	
	$libraries1 = [$library1, $library2, $library7];
	echo bobzanLibrary($libraries1);
	echo '<br />';

	$libraries2 = [$library2];
	echo bobzanLibrary($libraries2);
	echo '<br />';

	echo $library1->mostExpensiveBook();
	echo '<br />';
	echo $library2->leastExpensiveBook();
	echo '<br />';
	echo $library7->totalValueOfBooks();
	echo '<br />';
?>