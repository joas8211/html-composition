<?php

class Composition {
    public $parent;
    public $start;
    public $end;
    public $result;

    function __construct($parent = null, $start = '', $end = '') {
        $this->parent = $parent;
        $this->start = $start;
        $this->end = $end;
        $this->result = $start;
    }

    function __toString() {
        return $this->result;
    }

    function end(): self {
        $this->result .= $this->end;
        $this->parent->result .= $this->result;
        return $this->parent;
    }
}

class HtmlComposition extends Composition {
    function html(string $html): self {
        $this->result .= $html;
        return $this;
    }
    
    function text(string $text): self {
        $this->result .= htmlentities($text);
        return $this;
    }

    function tag(string $name, array $attributes = [], bool $isVoid = false): self {
        $attributeString = '';
        foreach ($attributes as $attr => $value) {
            $attr = htmlentities($attr);
            $value = htmlentities($value);
            $attributeString .= " $attr=\"$value\"";
        }
        $attributes = implode(' ', $attributes);
        if ($isVoid) {
            return $this->html("<$name$attributeString>");
        } else {
            return new static($this, "<$name$attributeString>", "</$name>");
        }
    }

    function div(array $attributes = []): self {
        return $this->tag('div', $attributes);
    }
    
    function h1(array $attributes = []): self {
        return $this->tag('h1', $attributes);
    }
    
    function p(array $attributes = []): self {
        return $this->tag('p', $attributes);
    }
    
    function img(array $attributes = []): self {
        return $this->tag('img', $attributes, true);
    }
}

trait HelloInsert {
    // Required inserts
    abstract function div(array $attributes = []): HtmlComposition;
    abstract function h1(array $attributes = []): HtmlComposition;
    abstract function p(array $attributes = []): HtmlComposition;

    function hello(array $attributes = []): self {
        return $this
            ->div($attributes)
                ->h1(['class' => 'title'])
                    ->text('Hello World!')
                ->end()
                ->p(['class' => 'content'])
                    ->text('Lorem ipsum')
                ->end()
            ->end();
    }
}

// Create new HTML composition with an additional insert
echo (new class extends HtmlComposition { use HelloInsert; })
    ->div()
        ->hello(['class' => 'hello'])
        ->img(['src' => 'https://placehold.it/100x100.jpg'])
    ->end();