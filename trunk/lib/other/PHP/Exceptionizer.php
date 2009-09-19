<?php ## ����� ��� �������������� ������ PHP � ����������.
/**
 * ����� ��� �������������� ��������������� (��. set_error_handler())
 * ������ � �������������� PHP � ����������.
 *
 * ��������� ���� ������, ���� � �������������� ���������, �� �����
 * ���� �����������:
 * E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR,
 * E_COMPILE_WARNING
 */
class PHP_Exceptionizer {
  // ������� ����� ������-����������� � ���������� ��� � �����
  // ������������ ������ PHP (������������ ��������� "���������
  // ������� ���� �������������").
  public function __construct($mask=E_ALL, $ignoreOther=false) {
    $catcher = new PHP_Exceptionizer_Catcher();
    $catcher->mask = $mask;
    $catcher->ignoreOther = $ignoreOther;
    $catcher->prevHdl = set_error_handler(array($catcher, "handler"));
  }
  // ���������� ��� ����������� �������-������������ (��������,
  // ��� ������ ��� �� ������� ��������� �������). ���������������
  // ���������� ���������� ������.
  public function __destruct() {
    restore_error_handler();
  }
}

/**
 * ���������� �����, ���������� ����� ��������� ������.
 * �� �� ����� ������������ ��� ���� �� ���� ��������������� $this
 * (������ PHP_Exceptionizer): ����� set_error_handler() �����������
 * ������� ������ �� ������, � �� ������ �������� ����������, ����� �
 * ��������� ������ ���������� ����� ���� ������.
 */
class PHP_Exceptionizer_Catcher {
  // ������� ����� ��������������, ������� ����� ���������������.
  public $mask = E_ALL;
  // �������, ����� �� ������������ ��������� ���� ������, ��� ��
  // ������� ������������ ����������� �������� ��������� PHP.
  public $ignoreOther = false;
  // ���������� ���������� ������.
  public $prevHdl = null;
  // �������-���������� ������ PHP.
  public function handler($errno, $errstr, $errfile, $errline) {
    // ���� error_reporting �������, ������, ����������� �������� @,
    // � ��� ������ ������ ��������������.
    if (!error_reporting()) return;
    // ����������� �� ������ ������������ ���� ��� ������?
    if (!($errno & $this->mask)) {
      // ���� ������ �� ������� ������������...
      if (!$this->ignoreOther) {
        if ($this->prevHdl) {
          // ���� ���������� ���������� ����������, �������� ���.
          $args = func_get_args();
          call_user_func_array($this->prevHdl, $args);
        } else {
          // ����� ���������� false, ��� �������� ������ �����������
          // ����������� PHP.
          return false;
        }
      }
      // ���������� true (��� �������).
      return true;
    }
    // �������� ��������� ������������� ���� ������.
    $types = array(
      "E_ERROR", "E_WARNING", "E_PARSE", "E_NOTICE", "E_CORE_ERROR",
      "E_CORE_WARNING", "E_COMPILE_ERROR", "E_COMPILE_WARNING",
      "E_USER_ERROR", "E_USER_WARNING", "E_USER_NOTICE", "E_STRICT",
      "E_RECOVERABLE_ERROR"
    );
    // ��������� ��� ������-���������� � ����������� �� ���� ������.
    $className = "E_EXCEPTION";
    foreach ($types as $t) {
      $e = constant($t);
      if ($errno & $e) {
        $className = $t;
        break;
      }
    }
    // ���������� ���������� ������� ����.
    throw new $className($errno, $errstr, $errfile, $errline);
  }
}

/**
 * ������� ����� ��� ���� ����������, ���������� � ���������� ������ PHP.
 */
abstract class PHP_Exceptionizer_Exception extends Exception {
  public function __construct($no=0, $str=null, $file=null, $line=0) {
    parent::__construct($str, $no);
    $this->file = $file;
    $this->line = $line;
  }
}

/**
 * ������� �������� "�����������" ������, ����� ����� ����
 * ������ �� ������ ���������� � ��������� ������� ����, ��
 * � ���������, �� ����� "���������", ��� �������.
 */
class E_EXCEPTION extends PHP_Exceptionizer_Exception {}
  class AboveE_STRICT extends E_EXCEPTION {}
    class E_STRICT extends AboveE_STRICT {}
    class AboveE_NOTICE extends AboveE_STRICT {}
      class E_NOTICE extends AboveE_NOTICE {}
      class AboveE_WARNING extends AboveE_NOTICE {}
        class E_WARNING extends AboveE_WARNING {}
        class AboveE_PARSE extends AboveE_WARNING {}
          class E_PARSE extends AboveE_PARSE {}
          class AboveE_RECOVERABLE_ERROR extends AboveE_PARSE {}
            class E_RECOVERABLE_ERROR extends AboveE_RECOVERABLE_ERROR {}
            class AboveE_ERROR extends AboveE_RECOVERABLE_ERROR {}
              class E_ERROR extends AboveE_ERROR {}
              class E_CORE_ERROR extends AboveE_ERROR {}
              class E_CORE_WARNING extends AboveE_ERROR {}
              class E_COMPILE_ERROR extends AboveE_ERROR {}
              class E_COMPILE_WARNING extends AboveE_ERROR {}
  class AboveE_USER_NOTICE extends E_EXCEPTION {}
    class E_USER_NOTICE extends AboveE_USER_NOTICE {}
    class AboveE_USER_WARNING extends AboveE_USER_NOTICE {}
      class E_USER_WARNING extends AboveE_USER_WARNING {}
      class AboveE_USER_ERROR extends AboveE_USER_WARNING {}
        class E_USER_ERROR extends AboveE_USER_ERROR {}
  // �������� ���������������� � ���������� ������ �� ��������,
  // �.�. ��� ������������ ��� ������ �����, � �������
  // "�����������" ������.
?>
