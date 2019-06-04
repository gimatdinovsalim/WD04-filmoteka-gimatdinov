<?php 



$link = mysqli_connect('localhost', 'root', '', 'WD04-filmoteka-gimatdinov'); //Подключение к базе данных

if ( mysqli_connect_error() ) {
	die("Ошибка подключения к базе данных"); //Выдает ошибку если не подключился, и прекратит выполнение скрипта дальше
}


$arrors = array();

// print_r($_GET);

//Удаление фильма

if ( @$_GET['action'] == 'delete') {
	// echo "delete";
	$query = "DELETE FROM films WHERE id = '" . mysqli_real_escape_string($link, $_GET['id']) ."' LIMIT 1";
	mysqli_query($link, $query);

	
	if (mysqli_affected_rows($link) > 0) {
		$resultInfo = "<p>Фильм удален</p>";
	}
}







if ( array_key_exists('newFilm', $_POST) ) { // Если в массиве Пост есть ключ newFilm, тогда выполняем следующий код
	
	//Обработка ошибок
	if ( $_POST['title'] == '') {
		$errors[] = "<p>Необходимо ввести название фильма!</p>";
	}
	if ( $_POST['genre'] == '') {
		$errors[] = "<p>Необходимо ввести жанр фильма!</p>";
	}
	if ( $_POST['year'] == '') {
		$errors[] = "<p>Необходимо ввести год фильма!</p>";
	}


	// Если ошибок нет сохраняем фильм
	if (empty($errors) ) {
	//Запись данных в ДБ

		$query = "INSERT INTO films (title, genre, year) VALUES (
		'". mysqli_real_escape_string($link, $_POST['title']) ."', 
		'". mysqli_real_escape_string($link, $_POST['genre']) ."',
		'". mysqli_real_escape_string($link, $_POST['year']) ."'
		)"; // В целях безопасности


	if (mysqli_query($link, $query)) {
			$resultSuccess = "<p>Фильм успешно добавлен</p>";	
	}else{
		$resultError = "<p>Ошибка</p>";
		}
	}

}
	


//Вывод информации films с базы данных
$query = "SELECT * FROM films";
//Помещаем данные массива
$films = array();

$result = mysqli_query($link, $query);
//Функия возвращает результат
if ($result = mysqli_query($link, $query)) { //Если результат положительный 
	while ($row = mysqli_fetch_array($result)) { //Формируем массив, каждый новый ряд таблицы будем записывать в $row
		$films[] = $row; //Каждый полученный ряд будем формировать в массив $films
	}
}


?>




<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8" />
	<title>[Имя и фамилия] - Фильмотека</title>
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"/><![endif]-->
	<meta name="keywords" content="" />
	<meta name="description" content="" /><!-- build:cssVendor css/vendor.css -->
	<link rel="stylesheet" href="libs/normalize-css/normalize.css" />
	<link rel="stylesheet" href="libs/bootstrap-4-grid/grid.min.css" />
	<link rel="stylesheet" href="libs/jquery-custom-scrollbar/jquery.custom-scrollbar.css" /><!-- endbuild -->
	<!-- build:cssCustom css/main.css -->
	<link rel="stylesheet" href="./css/main.css" /><!-- endbuild -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800&amp;subset=cyrillic-ext" rel="stylesheet">
	<!--[if lt IE 9]><script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script><![endif]-->
		<style>
			.info-success {
				width: 100%;
				margin-bottom: 20px;
				font-size: 16px;
				line-height: 1.3;
				background-color: Chartreuse;
				border-radius: 5px;
				color: white;
				text-align: center;
				}
			.info-notification{
				width: 100%;
				margin-bottom: 20px;
				font-size: 16px;
				line-height: 1.3;
				background-color: Olive;
				border-radius: 5px;
				color: white;
				text-align: center;
			}
			.error{
				width: 100%;
				margin-bottom: 20px;
				font-size: 16px;
				line-height: 1.3;
				background-color: LightCoral;
				border-radius: 5px;
				color: LightYellow;
				text-align: center;
			}
		</style>
</head>

<body class="index-page">
	<div class="container user-content section-page">



<?php if ( @$resultSuccess != '' ) { ?>
		<div class="info-success"><?=$resultSuccess?></div> 
<?php } ?>

<?php if ( @$resultInfo != '' ) { ?>
		<div class="info-notification"><?=$resultInfo?></div> 
<?php } ?>

<?php if ( @$resultError != '' ) { ?>
		<div class="error"><?=$resultError?></div> 
<?php } ?>





<div class="panel-holder mt-80 mb-40">
<div class="title-3 mt-0">Добавить фильм</div>
		

	<form action="index.php" method="POST">


<?php 
if (!empty($errors)) {
	foreach ($errors as $key => $value) {
	echo "<div class='error'>$value</div>";
	}
}
?>


			<div class="form-group"><label class="label">Название фильма<input class="input" name="title" type="text" placeholder="Такси 2" /></label></div>
			<div class="row">
				<div class="col">
					<div class="form-group"><label class="label">Жанр<input class="input" name="genre" type="text" placeholder="комедия" /></label></div>
				</div>
				<div class="col">
					<div class="form-group"><label class="label">Год<input class="input" name="year" type="text" placeholder="2000" /></label></div>
				</div>
			</div><input class="button" type="submit" name="newFilm" value="Добавить" />

		</form>



		</div>




	<div class="title-1">Фильмотека</div>


<?php 
	foreach ($films as $key => $film) {
?>

	<div class="card mb-20">
		<div class="card__header">
			<h4 class="title-4"><?=$film['title']?></h4>
			<a href="?action=delete&id=<?=$film['id']?>" class="button button--delete">Удалить</a>
			<a href="edit.php?id=<?=$film['id']?>" class="button button--edit">Редактировать</a>
		</div>
		
		<div class="badge"><?=$film['genre']?></div>
		<div class="badge"><?=$film['year']?></div>
	</div>

<?php } ?>
		
		

	</div><!-- build:jsLibs js/libs.js -->
	<script src="libs/jquery/jquery.min.js"></script><!-- endbuild -->
	<!-- build:jsVendor js/vendor.js -->
	<script src="libs/jquery-custom-scrollbar/jquery.custom-scrollbar.js"></script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIr67yxxPmnF-xb4JVokCVGgLbPtuqxiA"></script><!-- endbuild -->
	<!-- build:jsMain js/main.js -->
	<script src="js/main.js"></script><!-- endbuild -->
	<script defer="defer" src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
</body>

</html>