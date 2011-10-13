<?php
class Calc {
	protected $glade;
	protected $firstParam = null;
	protected $operation = null;
	
	function __construct($glade) {
		$this->glade = $glade;
	}
	/* Посчитать результат операции */
	protected function calculate($operation) {
		$secondParam = (float) $this->glade->get_widget('entry1')->get_text();
		$firstParam = (float) $this->firstParam;
		$result = 0;
		switch($this->operation) {
			case '*': 
				$result = $firstParam * $secondParam; 
				break;
			case '/': 
				$result = ($secondParam > 0 ? $firstParam / $secondParam : 0); 
				break;
			case '+': 
				$result = $firstParam + $secondParam; 
				break;
			case '-': 
				$result = $firstParam - $secondParam; 
				break;
		}
		$this->glade->get_widget('entry1')->set_text($result);
		$this->firstParam = $result;
		$this->operation = null;
	}
	/* Выполнить операцию */
	public function performAction($obj) {
		if ($this->firstParam == null) {
			$this->firstParam = $this->glade->get_widget('entry1')->get_text();
			$this->glade->get_widget('entry1')->set_text('');
		} 
		if ($this->operation == null) {
			$this->operation = str_replace(
				array('action_mul', 'action_add', 'action_min', 'action_div'), 
				array('*', '+', '-', '/'), 
				$obj->name
			);
			$this->glade->get_widget('entry1')->set_text('');
		} else {
			$this->calculate($obj->name);
		}
	}
	/* Добавить символ к текущему значению поля ввода */
	public function enterValue($obj) {
		/* Так как у нас имена кнопок input0..input1, нужно вырезать "input" и получим значение */
		/* Знаю что глупо, но задача статьи не написать алгоритм для калькулятора) */
		$this->glade->get_widget('entry1')->set_text(
			$this->glade->get_widget('entry1')->get_text().
			str_replace('input', '', $obj->name)
		);
	}
	/* Очистить поле ввода,а также значение первой переменной и выполняемую операцию */
	public function clearCalc($obj) {
		$this->firstParam = null;
		$this->operation = null;
		$this->glade->get_widget('entry1')->set_text('');
	}
	/* Выход */
	public function quit() {
		exit;
	}
}
/* Загрузить файл интефейса */
$glade = new GladeXML('calc.glade');
/* Указать, что все обработчики событий в классе Calc */
$glade->signal_autoconnect_instance(new Calc($glade));
/* При закрытии окна закрывать приложение */
$glade->get_widget('window1')->connect_simple('destroy', array('Gtk', 'main_quit'));
/* Инициализация GTK приложения */
Gtk::main();

