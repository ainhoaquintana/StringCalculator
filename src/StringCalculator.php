<?php

namespace Deg540\PHPTestingBoilerplate;

use SebastianBergmann\CliParser\RequiredOptionArgumentMissingException;
use function PHPUnit\Framework\isEmpty;

class StringCalculator
{

    public function add(string $valueString): string
    {
        $sum = 0;
        $errors ="";
        $customizedSeparator = "";

        if(empty($valueString)){
            return strval($sum);
        }
        else {
            //GET THE NEW SEPARATOR IF THE STRING STARTS WITH "//"
            if ($valueString[0] == "/") {
                $customizedSeparatorIterator = 2;
                while ($valueString[$customizedSeparatorIterator] != "\n") {
                    $customizedSeparator .= $valueString[$customizedSeparatorIterator];
                    $customizedSeparatorIterator++;
                }
                $head = "//$customizedSeparator\n";
                $valueString = str_replace($head, "", $valueString);
            }

            $negativeNumbers="";
            $anterior = $valueString[0];

            //CHECK IF THERE IS A SEPARATOR NEAR ANOTHER
            if(!empty($customizedSeparator)){
                if (str_contains($valueString, "$customizedSeparator$customizedSeparator")) {
                    $customizedSeparatorPos = strpos($valueString, "$customizedSeparator") + 1;
                    $errors .= "Number expected but $customizedSeparator found at position $customizedSeparatorPos\n";
                }
            }else{
                if (str_contains($valueString, ",,")) {
                    $commaPos = strpos($valueString, ",,") + 1;
                    $errors .= "Number expected but ',' found at position $commaPos\n";
                }
                if (str_contains($valueString, "\n\n")) {
                    $newlinePos = strpos($valueString, "\n\n") + 1;
                    $errors .= "Number expected but '\n' found at position $newlinePos\n";
                }
                if (str_contains($valueString, ",\n")) {
                    $newlinePos = strpos($valueString, ",\n") + 1;
                    $errors .= "Number expected but '\n' found at position $newlinePos\n";
                }
                if (str_contains($valueString, "\n,")) {
                    $commaPos = strpos($valueString, "\n,") + 1;
                    $errors .= "Number expected but ',' found at position $commaPos\n";
                }
            }

            //CHECK IF STRING ENDS WITH SEPARATOR
            if (str_ends_with($valueString, ",") or str_ends_with($valueString, "\n")) {
                $errors .= "Number expected but EOF found\n";
            }

            //SEPARATE THE STRING
            $separatedStringArray = [];
            if (empty($customizedSeparator)) {
                $separatedStringArray = preg_split('/(,|\n)/', $valueString);

                //CHECK IF STRING ENDS WITH SEPARATOR
                if (str_ends_with($valueString, ",") or str_ends_with($valueString, "\n")) {
                    $errors .= "Number expected but EOF found\n";
                }
            } else {

                //CHECK IF STRING ENDS WITH SEPARATOR
                if (str_ends_with($valueString, "$customizedSeparator")){
                    $errors .= "Number expected but EOF found\n";
                }
                //CHECK IF THERE IS A COMMA OR A NEWLINE WHEN THERE IS A CUSTOMIZED SEPARATOR
                if (str_contains($valueString, ",")) {
                    $commaPos = strpos($valueString, ",");
                    $errors .= "'$customizedSeparator' expected but ',' found in position $commaPos\n";
                } elseif (str_contains($valueString, "\n")) {
                    $newlinePos = strpos($valueString, "\n");
                    $errors .= "'$customizedSeparator' expected but '\n' found in position $newlinePos\n";
                } else {
                    $separatedStringArray = explode($customizedSeparator, $valueString);
                }
            }

            //CHECK IF THE STRING CONTAINS NEGATIVE NUMBERS
            foreach($separatedStringArray as $negativeNumber){
                if(str_contains( $negativeNumber, "-")){
                    $negativeNumbers .= " $negativeNumber,";
                }
            }
            if(!empty($negativeNumbers)) {
                $negativeNumbers = substr($negativeNumbers, 0, strlen($negativeNumbers)-1); //DELETE THE LAST NUMBER'S FINAL COMMA
                $errors .= "Negative not allowed :$negativeNumbers\n";
            }
            //RETURN THE ERRORS OR GET THE SUM
            if(!empty($errors)){
                $errors = substr($errors, 0, strlen($errors)-1); //DELETE THE LAST ERROR'S \n
                return($errors);
            }
            else{
                foreach ($separatedStringArray as $number)
                    $sum = $sum + $number;
                return (strval($sum));
            }
        }
    }
}