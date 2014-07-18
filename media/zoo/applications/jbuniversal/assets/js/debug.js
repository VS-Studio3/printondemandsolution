/**
 * JBZoo is universal CCK based Joomla! CMS and YooTheme Zoo component
 * @category   JBZoo
 * @author     smet.denis <admin@joomla-book.ru>
 * @copyright  Copyright (c) 2009-2012, Joomla-book.ru
 * @license    http://joomla-book.ru/info/disclaimer
 * @link       http://joomla-book.ru/projects/jbzoo JBZoo project page
 */


/**
 * Check is variable empty
 * @link http://phpjs.org/functions/empty:392
 * @param mixedVar
 * @return Boolean
 */
function empty(mixedVar) {

    // check simple var
    if (typeof mixedVar === 'undefined'
        || mixedVar === ""
        || mixedVar === 0
        || mixedVar === "0"
        || mixedVar === null
        || mixedVar === false
        ) {
        return true;
    }

    // check object
    if (typeof mixedVar == 'object') {
        if (countProps(mixedVar) == 0) {
            return true
        }
    }

    return false;
}

/**
 * Alias for console log + backtrace
 * @param vars
 * @param name String
 * @param showTrace Boolean|int
 */
function dump(vars, name, showTrace) {

    // is console exists
    if (typeof console == 'undefined') {
        return false;
    }

    // get type
    if (typeof vars == 'string' || typeof vars == 'array') {
        var type = ' (' + typeof(vars) + ', ' + vars.length + ')';
    } else {
        var type = ' (' + typeof(vars) + ')';
    }

    // wrap in vars quote if string
    if (typeof vars == 'string') {
        vars = '"' + vars + '"';
    }

    // get var name
    if (typeof name == 'undefined') {
        name = '...' + type + ' = ';
    } else {
        name += type + ' = ';
    }

    // is show trace in console
    if (typeof showTrace == 'undefined') {
        showTrace = false;
    }

    // dump var
    console.log(name, vars);

    // show console
    if (showTrace && typeof console.trace != 'undefined') {
        console.trace();
    }

    return true;
}

/**
 * Backtrace for debug
 * @param asString Boolean
 */
function trace(asString) {

    if (empty(asString)) {
        asString = false;
    }

    var getStackTrace = function () {
        var obj = {};
        Error.captureStackTrace(obj, getStackTrace);
        return obj.stack;
    };

    if (asString) {
        dump(getStackTrace(), 'trace', false);
    } else {
        if (typeof console != 'undefined') {
            console.trace();
        }
    }

}

/**
 * Die script
 * @param dieMessage String
 */
function die(dieMessage) {
    if (empty(dieMessage)) {
        trace();
        dieMessage = ' -= ! DIE ! =-';
    }
    throw dieMessage;
}