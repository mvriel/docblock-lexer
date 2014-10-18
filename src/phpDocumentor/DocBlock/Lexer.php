<?php

namespace phpDocumentor\DocBlock;

class Lexer
{
    const SYMBOL_PHPDOC      = 'PHPD';
    const SYMBOL_DESCRIPTION = 'DESC';
    const SYMBOL_TAGS        = 'TAGS';

    const T_SUMMARY = 1;
    const T_DESCRIPTION_LITERAL = 2;
    const T_INLINE_TAG_OPEN = 3;
    const T_INLINE_TAG_CLOSE = 4;
    const T_TAG_AUTHOR = 5;
    const T_TAG_AUTHOR_ADDRESS_OPEN = 6;
    const T_TAG_AUTHOR_ADDRESS = 7;
    const T_TAG_AUTHOR_ADDRESS_CLOSE = 8;
    const T_TAG_API = 9;
    const T_TAG_CATEGORY = 10;
    const T_TAG_CATEGORY_NAME = 11;
    const T_TAG_COPYRIGHT = 12;
    const T_TAG_DEPRECATED = 13;
    const T_TAG_EXAMPLE = 14;
    const T_TAG_EXAMPLE_REFERENCE = 15;
    const T_TAG_FILE_SOURCE = 16;
    const T_TAG_GLOBAL = 17;
    const T_TAG_GLOBAL_NAME = 18;
    const T_TAG_IGNORE = 19;
    const T_TAG_INTERNAL = 20;
    const T_TAG_LICENSE = 21;
    const T_TAG_LICENSE_NAME = 22;
    const T_TAG_LINK = 23;
    const T_TAG_LINK_REFERENCE = 24;
    const T_TAG_METHOD = 25;
    const T_TAG_PACKAGE = 26;
    const T_TAG_PACKAGE_NAME = 27;
    const T_TAG_PARAM = 28;
    const T_ANNOTATION = 29;
    const T_TAG_PARAM_NAME = 30;
    const T_TAG_PROPERTY = 31;
    const T_ANNOTATION_SIGNATURE = 32;
    const T_TAG_PROPERTY_NAME = 33;
    const T_TAG_PROPERTY_READ = 34;
    const T_ANNOTATION_SIGNATURE_OPEN = 35;
    const T_ANNOTATION_SIGNATURE_CLOSE = 36;
    const T_TAG_PROPERTY_WRITE = 37;
    const T_TAG_RETURN = 40;
    const T_TYPE = 41;
    const T_TAG_SEE = 42;
    const T_TAG_SEE_REFERENCE = 43;
    const T_TAG_SINCE = 44;
    const T_VERSION = 45;
    const T_TAG_SOURCE = 46;
    const T_TAG_SUBPACKAGE = 47;
    const T_TAG_SUBPACKAGE_NAME = 48;
    const T_TAG_THROWS = 49;
    const T_EXCEPTION_TYPE = 50;
    const T_TAG_TODO = 51;
    const T_TAG_USES = 52;
    const T_TAG_USES_REFERENCE = 53;
    const T_TAG_VAR = 54;
    const T_TAG_VERSION = 56;
    const T_UNKNOWN_TAG = 58;
    const T_TAG_AUTHOR_NAME = 59;
    const T_TAG_DESCRIPTION = 60;
    const T_TEMPLATE = 61;

    private $tokenMap = array(
        'PHPD1' => self::T_TEMPLATE,
        'PHPD2' => self::T_SUMMARY,
        'DESC1' => self::T_DESCRIPTION_LITERAL,
        'DESC2' => self::T_INLINE_TAG_OPEN,
        'DESC4' => self::T_INLINE_TAG_CLOSE,
        'TAGS1' => self::T_ANNOTATION,
        'TAGS2' => self::T_ANNOTATION_SIGNATURE_OPEN,
        'TAGS3' => self::T_ANNOTATION_SIGNATURE,
        'TAGS4' => self::T_ANNOTATION_SIGNATURE_CLOSE,
        'TAGS5' => self::T_TAG_AUTHOR,
        'TAGS6' => self::T_TAG_AUTHOR_NAME,
        'TAGS7' => self::T_TAG_AUTHOR_ADDRESS_OPEN,
        'TAGS8' => self::T_TAG_AUTHOR_ADDRESS,
        'TAGS9' => self::T_TAG_AUTHOR_ADDRESS_CLOSE,
        'TAGS10' => self::T_TAG_API,
        'TAGS11' => self::T_TAG_CATEGORY,
        'TAGS12' => self::T_TAG_CATEGORY_NAME,
        'TAGS13' => self::T_TAG_COPYRIGHT,
        'TAGS14' => self::T_TAG_DEPRECATED,
        'TAGS15' => self::T_TAG_EXAMPLE,
        'TAGS16' => self::T_TAG_EXAMPLE_REFERENCE,
        'TAGS17' => self::T_TAG_FILE_SOURCE,
        'TAGS18' => self::T_TAG_GLOBAL,
        'TAGS19' => self::T_TAG_GLOBAL_NAME,
        'TAGS20' => self::T_TAG_IGNORE,
        'TAGS21' => self::T_TAG_INTERNAL,
        'TAGS22' => self::T_TAG_LICENSE,
        'TAGS23' => self::T_TAG_LICENSE_NAME,
        'TAGS24' => self::T_TAG_LINK,
        'TAGS25' => self::T_TAG_LINK_REFERENCE,
        'TAGS26' => self::T_TAG_METHOD,
        'TAGS27' => self::T_TAG_PACKAGE,
        'TAGS28' => self::T_TAG_PACKAGE_NAME,
        'TAGS29' => self::T_TAG_PARAM,
        'TAGS30' => self::T_TYPE,
        'TAGS31' => self::T_TAG_PARAM_NAME,
        'TAGS32' => self::T_TAG_PROPERTY,
        'TAGS33' => self::T_TYPE,
        'TAGS34' => self::T_TAG_PROPERTY_NAME,
        'TAGS35' => self::T_TAG_PROPERTY_READ,
        'TAGS36' => self::T_TYPE,
        'TAGS37' => self::T_TAG_PROPERTY_NAME,
        'TAGS38' => self::T_TAG_PROPERTY_WRITE,
        'TAGS39' => self::T_TYPE,
        'TAGS40' => self::T_TAG_PROPERTY_NAME,
        'TAGS41' => self::T_TAG_RETURN,
        'TAGS42' => self::T_TYPE,
        'TAGS43' => self::T_TAG_SEE,
        'TAGS44' => self::T_TAG_SEE_REFERENCE,
        'TAGS45' => self::T_TAG_SINCE,
        'TAGS46' => self::T_VERSION,
        'TAGS47' => self::T_TAG_SOURCE,
        'TAGS48' => self::T_TAG_SUBPACKAGE,
        'TAGS49' => self::T_TAG_SUBPACKAGE_NAME,
        'TAGS50' => self::T_TAG_THROWS,
        'TAGS51' => self::T_EXCEPTION_TYPE,
        'TAGS52' => self::T_TAG_TODO,
        'TAGS53' => self::T_TAG_USES,
        'TAGS54' => self::T_TAG_USES_REFERENCE,
        'TAGS55' => self::T_TAG_VAR,
        'TAGS56' => self::T_TYPE,
        'TAGS57' => self::T_TAG_VERSION,
        'TAGS58' => self::T_VERSION,
        'TAGS59' => self::T_UNKNOWN_TAG,
        'TAGS60' => self::T_TAG_DESCRIPTION,
    );

    private $transitionTable = array(
        self::SYMBOL_PHPDOC => array(
            3 => self::SYMBOL_DESCRIPTION,
            4 => self::SYMBOL_TAGS,
        ),
        self::SYMBOL_DESCRIPTION => array(
            3 => self::SYMBOL_TAGS
        )
    );

    /** @var \SplStack */
    private $stack;

    private $tokens = array();

    private $symbols = array(
        self::SYMBOL_PHPDOC =>
            '/
            \A
            # 1. Extract the template marker
            (?:(\#\@\+|\#\@\-)\n?)?

            # 2. Extract the summary
            (?:
              (?! @\pL ) # The summary may not start with an @
              (
                [^\n.]+
                (?:
                  (?! \. \n | \n{2} )     # End summary upon a dot followed by newline or two newlines
                  [\n.] (?! [ \t]* @\pL ) # End summary when an @ is found as first character on a new line
                  [^\n.]+                 # Include anything else
                )*
                \.?
              )?
            )

            # 3. Extract the description
            (?:
              \s*        # Some form of whitespace _must_ precede a description because a summary must be there
              (?! @\pL ) # The description may not start with an @
              (
                [^\n]+
                (?: \n+
                  (?! [ \t]* @\pL ) # End description when an @ is found as first character on a new line
                  [^\n]+            # Include anything else
                )*
              )
            )?

            # 4. Extract the tags (anything that follows)
            (\s+ [\s\S]*)? # everything that follows
            /ux',
        self::SYMBOL_DESCRIPTION =>
            '/
            ([^{]*)|
            ({)
            ([^}]*)
            (})
            /ux',
        self::SYMBOL_TAGS =>
            '/(?:\A|\n)\s*
            (?:(@[\pL-_\\\\]+)\ *(\()([^\)]*)(\)))|
            (?:
              (?:
                (?:(@author)\ +([^<]+)(?:(\<)([^>]+)(\>))?)|
                (@api)|
                (@category\ +([^ ]+))|
                (@copyright)|
                (@deprecated)|
                (@example\ +([^ ]+))|
                (@filesource)|
                (@global\ +([^ ]+))|
                (@ignore)|
                (@internal)|
                (@license\ +([^ ]+))|
                (?:(@link)\ +([^ ]+))|
                (@method)|
                (@package\ +([^ ]+))|
                (@param\ +([^ ]+)\ +([^ ]+))|
                (@property\ +([^ ]+)\ +([^ ]+))|
                (@property-read\ +([^ ]+)\ +([^ ]+))|
                (@property-write\ +([^ ]+)\ +([^ ]+))|
                (@return\ +([^ ]+))|
                (?:(@see)\ +([^ ]+))|
                (@since\ +([^ ]+))|
                (@source)|
                (@subpackage\ +([^ ]+))|
                (@throws\ +([^ ]+))|
                (@todo)|
                (@uses\ +([^ ]+))|
                (@var\ +([^ ]+))|
                (@version\ +([^ ]+))|
                (@[\pL-_]+)
              )
              \ *
              (?s:(.*?))
            )
            (?=\n\s*@|\Z)
            /ux',
    );

    public function __construct()
    {
        $this->stack = new \SplStack();
        $this->stack->push(self::SYMBOL_PHPDOC);
        $this->reset();
    }

    public function reset()
    {
        $this->tokens = array();
    }

    public function lex($body)
    {
        $body = $this->normalizeLineEndings($body);
        $body = $this->removeDocCommentAsterisks($body);
        $this->reset();

        return $this->tokenize($body);
    }

    private function tokenize($body)
    {
        $currentSymbol = $this->stack->top();
        if (preg_match_all($this->symbols[$currentSymbol], $body, $tokensets, PREG_SET_ORDER) === false) {
            throw new \Exception('Failed to lex ' . $this->symbols[$currentSymbol]);
        }

        foreach ($tokensets as $tokenset) {
            // by filtering all empty tokens we expedite the foreach
            $tokenset = array_filter($tokenset);
            foreach ($tokenset as $state => $tokenValue) {
                if (isset($this->transitionTable[$currentSymbol][$state])) {
                    $this->stack->push($this->transitionTable[$currentSymbol][$state]);
                    $this->tokenize($tokenValue);
                    $this->stack->pop();
                } elseif ($state !== 0) {
                    $this->tokens[] = array(
                        'type'  => $this->tokenMap[$currentSymbol . $state],
                        'value' => $tokenValue
                    );
                }
            }
        }

        return $this->tokens;
    }

    public function getTokenName($id)
    {
        $class = new \ReflectionClass(__CLASS__);
        $constants = array_flip($class->getConstants());

        return $constants[$id];
    }

    /**
     * @param $string
     */
    private function normalizeLineEndings($comment)
    {
        return str_replace(array("\r\n", "\r"), "\n", $comment);
    }

    /**
     * @param $comment
     * @return mixed|string
     */
    private function removeDocCommentAsterisks($comment)
    {
        if (! $this->isPhpDocInDocComment($comment)) {
            return $comment;
        }

        $comment = preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ \t]{0,1}(.*)?#u', '$1', $comment);

        // reg ex above is not able to remove */ from a single line docblock
        if (substr($comment, -2) === '*/') {
            $comment = trim(substr($comment, 0, -2));
        }

        return $comment;
    }

    /**
     * @param $phpdoc
     * @return bool
     */
    private function isPhpDocInDocComment($phpdoc)
    {
        return substr($phpdoc, 0, 3) === '/**';
    }
}
