<?php

require_once('lib/bootstrap.php');
 
class StrTest extends PHPUnit_Framework_TestCase {

  protected $sample = 'Super Äwesøme String';

  public function testHtml() {
    $this->assertEquals('Super &Auml;wes&oslash;me String', str::html($this->sample));
  }

  public function testUnhtml() {
    $this->assertEquals($this->sample, str::unhtml('Super &Auml;wes&oslash;me String'));
  }

  public function testXml() {
    $this->assertEquals('Super &#196;wes&#248;me String', str::xml($this->sample));
  }

  public function testUnxml() {
    $this->assertEquals($this->sample, str::unxml('Super &#196;wes&#248;me String'));
  }

  public function testParse() {
    // no test yet
  }

  public function testEncode() {
    // no test yet
  }

  public function testEmail() {
    // no test yet
  }

  public function testLink() {
    
    // without text
    $this->assertEquals('<a href="http://getkirby.com">http://getkirby.com</a>', str::link('http://getkirby.com'));
    
    // with text
    $this->assertEquals('<a href="http://getkirby.com">Kirby</a>', str::link('http://getkirby.com', 'Kirby'));

  }

  public function testShort() {
    
    // too long
    $this->assertEquals('Super…', str::short($this->sample, 5));
    
    // not too long
    $this->assertEquals($this->sample, str::short($this->sample, 100));

    // zero chars
    $this->assertEquals($this->sample, str::short($this->sample, 0));

    // with different ellipsis character
    $this->assertEquals('Super---', str::short($this->sample, 5, '---'));

  }

  public function testExcerpt() {
    // no test yet
  }

  public function testSubstr() {

    $this->assertEquals($this->sample, str::substr($this->sample, 0));

    $this->assertEquals('Super', str::substr($this->sample, 0, 5));

    $this->assertEquals(' Äwes', str::substr($this->sample, 5, 5));

    $this->assertEquals(' Äwesøme String', str::substr($this->sample, 5));

    $this->assertEquals('tring', str::substr($this->sample, -5));

  }

  public function testLower() {
    $this->assertEquals('super äwesøme string', str::lower($this->sample));
  }

  public function testUpper() {
    $this->assertEquals('SUPER ÄWESØME STRING', str::upper($this->sample));
  }
  
  public function testLength() {
    $this->assertEquals(20, str::length($this->sample));
  }

  public function testContains() {

    $this->assertTrue(str::contains($this->sample, 'Äwesøme'));
    $this->assertTrue(str::contains($this->sample, 'äwesøme'));

    // don't ignore upper/lowercase
    $this->assertFalse(str::contains($this->sample, 'äwesøme', false));

    // check for something which isn't there    
    $this->assertFalse(str::contains($this->sample, 'Peter'));

  }

  public function testMatch() {
    // no test yet
  }

  public function testRandom() {
    // no test yet
  }

  public function testSlug() {

    // Double dashes
    $this->assertEquals('a-b', str::slug('a--b'));

    // Dashes at the end of the line
    $this->assertEquals('a', str::slug('a-'));

    // Dashes at the beginning of the line
    $this->assertEquals('a', str::slug('-a'));

    // Underscores converted to dashes
    $this->assertEquals('a-b', str::slug('a_b'));

    // Unallowed characters
    $this->assertEquals('a-b', str::slug('a@b'));

    // Spaces characters
    $this->assertEquals('a-b', str::slug('a b'));

    // Double Spaces characters
    $this->assertEquals('a-b', str::slug('a  b'));

    // Custom separator
    $this->assertEquals('a+b', str::slug('a-b', '+'));

    // Allow underscores
    $this->assertEquals('a_b', str::slug('a_b', '-', 'a-z0-9_'));

    // store default defaults
    $defaults = str::$defaults['slug'];

    // Custom str defaults
    str::$defaults['slug']['separator'] = '+';
    str::$defaults['slug']['allowed']   = 'a-z0-9_';

    $this->assertEquals('a+b', str::slug('a-b'));
    $this->assertEquals('a_b', str::slug('a_b'));

    // Reset str defaults
    str::$defaults['slug'] = $defaults;

  }

  public function testSplit() {

    $array = array('Super', 'Äwesøme', 'String');
    $this->assertEquals($array, str::split($this->sample, ' '));

    $array = array('Äwesøme', 'String');
    $this->assertEquals($array, str::split($this->sample, ' ', 6));

    $array = array('design', 'super', 'fun', 'great', 'nice/super');
    $this->assertEquals($array, str::split('design, super,, fun, great,,, nice/super'));

  }
  
  public function testUcwords() {

    $string = str::lower($this->sample);
    $this->assertEquals($this->sample, str::ucwords($string));
    
  }

  public function testUcfirst() {

    $string = str::lower($this->sample);

    $this->assertEquals('Super äwesøme string', str::ucfirst($string));
    
  }

  public function testUtf8() {

    $this->assertEquals($this->sample, str::utf8($this->sample));
    
  }

  public function testStripslashes() {
    // no test yet
  }
     
}

?>