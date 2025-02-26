<?php
function replaceSpacesWithUnderscores(string $input): string {
    return preg_replace('/\s+/', '_', trim($input));
}


print(replaceSpacesWithUnderscores("  Это  тестовая строка   с пробелами   "));