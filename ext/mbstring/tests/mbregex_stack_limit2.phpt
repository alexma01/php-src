--TEST--
Test oniguruma stack limit
--SKIPIF--
<?php extension_loaded('mbstring') or die('skip mbstring not available'); ?>
--XFAIL--
Travis CI has old oniguruma library
--FILE--
<?php
function mb_trim( $string, $chars = "", $chars_array = array() )
{
    for( $x=0; $x<iconv_strlen( $chars ); $x++ ) $chars_array[] = preg_quote( iconv_substr( $chars, $x, 1 ) );
    $encoded_char_list = implode( "|", array_merge( array( "\s","\t","\n","\r", "\0", "\x0B" ), $chars_array ) );

    $string = mb_ereg_replace( "^($encoded_char_list)*", "", $string );
    $string = mb_ereg_replace( "($encoded_char_list)*$", "", $string );
    return $string;
}

ini_set('mbstring.regex_stack_limit', 10000);
var_dump(mb_trim(str_repeat(' ', 10000)));

echo 'OK';
?>
--EXPECTF--
Warning: mb_ereg_replace(): mbregex search failure in php_mbereg_replace_exec(): match-stack limit over in %s on line %d
string(0) ""
OK
