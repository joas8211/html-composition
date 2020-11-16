<?php

class HtmlComposition
{
    public const DEFAULT_OPTIONS = [
        'pretty' => true,
        'indentation' => 0,
    ];

    /** @var array */
    public $options;

    /** @var self|null */
    public $parent;

    /** @var self[] */
    public $content = [];

    /** @var string */
    public $start;

    /** @var string */
    public $end;

    public function __construct(
        array $options = self::DEFAULT_OPTIONS,
        self $parent = null,
        string $start = '',
        string $end = ''
    ) {
        $this->options = array_merge(
            self::DEFAULT_OPTIONS,
            $options
        );
        $this->parent = $parent;
        $this->start = $start;
        $this->end = $end;
    }

    /** @param self|self[] $content */
    public function append($content): self
    {
        $indentation = $this->options['indentation'];
        if ($this->parent) {
            $indentation++;
        }
        if (!is_array($content)) {
            $content = [$content];
        }
        foreach ($content as $child) {
            $child->options['indentation'] = $indentation;
            $this->content[] = $child;
        }
        return $this;
    }

    public function html(string $html): self
    {
        $this->append(new self($this->options, $this, $html));
        return $this;
    }
    
    public function text(string $text): self
    {
        return $this->html(htmlentities($text));
    }

    public function document($header = '<!doctype html>'): self
    {
        return $this->html($header);
    }

    public function tag(string $name, array $attributes = [], bool $isVoid = false): self
    {
        $attributeString = '';
        foreach ($attributes as $attr => $value) {
            if (is_numeric($attr)) {
                $value = htmlentities($value);
                $attributeString .= " $value";
            } else {
                $attr = htmlentities($attr);
                $value = htmlentities($value);
                $attributeString .= " $attr=\"$value\"";
            }
        }
        $attributes = implode(' ', $attributes);
        if ($isVoid) {
            return $this->html("<$name$attributeString>");
        } else {
            $child = new self(
                $this->options,
                $this,
                "<$name$attributeString>",
                "</$name>"
            );
            $this->append($child);
            return $child;
        }
    }

    public function end(): self
    {
        return $this->parent;
    }

    protected function indent(string $value): string
    {
        $indentationAmount = ($this->options['indentation'] ?? 0);
        $indentation = str_pad('', $indentationAmount * 4);
        return $indentation . $value;
    }

    public function __toString(): string
    {
        $pretty = $this->options['pretty'] ?? true;
        if (!empty($this->content)) {
            $output = [];
            if ($this->start) {
                $output[] = $pretty ?
                    $this->indent($this->start) :
                    $this->start;
            }
            $output = array_merge($output, $this->content);
            if ($this->end) {
                $output[] = $pretty ?
                    $this->indent($this->end) :
                    $this->end;
            }
            return implode($pretty ? "\n" : '', $output);
        } else {
            $output = [];
            if ($this->start) {
                $output[] = $this->start;
            }
            if ($this->end) {
                $output[] = $this->end;
            }
            $output = implode('', $output);
            return $pretty ? $this->indent($output) : $output;
        }
    }
}
