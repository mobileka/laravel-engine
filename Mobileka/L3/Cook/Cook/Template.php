<?php namespace Cook;

use Laravel\Str;
use Laravel\File;
use Symfony\Component\Console\Input\ArgvInput;
use Laravel\Bundle;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Template {

	// Template root folder
	public $root;
	public $path;

	// Constructor container
	public $constructor;

	// Templates list
	public $templates;

	public $templateName;
	public $name;
	public $newName;
	public $replacerObject;
	public $content;
	public $result;

	public $tokens;
	public $tabs;
	public $replacers;

	public $resultPath;
	public $resultPathFromBundle;
	public $resultPathWithFilename;

	public function setConstructor(Constructor $constructor)
	{
		$this->constructor = $constructor;

		$this->checkInput();

		$this->findTemplatesRecursive();

		$this->setTemplatesPaths();

		$this->findReplacerObjects();

		$this->findTokens();

		$this->findReplacers();

		return $this;
	}

	protected function checkInput()
	{
		$input = new ArgvInput;

		$name = $input->getParameterOption('--tpl');

		if ($name === false)
		{
			throw new CException("continue", 500);
		}

		if ($name === null)
		{
			throw new CException("Set template name.");
		}

		$this->root = Bundle::path('cook') . 'Cook' . DS . 'Templates' . DS . $name;

		if (!is_dir($this->root))
		{
			throw new CException("Template '$name' not found.");
		}

		$this->templateName = $name;
	}

	protected function findTemplatesRecursive()
	{
		$directoryIterator = new RecursiveDirectoryIterator($this->root);
		$recursiveIterator = new RecursiveIteratorIterator($directoryIterator);

		foreach ($recursiveIterator as $file) 
		{
			$info = pathinfo($file);

			if (!is_dir($file) and $info['extension'] == 'tpl')
			{
				$template = new static;

				$template->content = File::get($file);
				$template->name = $info['filename'];
				$template->root = $info['dirname'];

				$this->templates[] = $template;
			}
		}
	}

	protected function findReplacerObjects()
	{
		foreach ($this->templates as $i => $template) 
		{
			$replacerPath = $template->root . DS . $template->name . 'Replacer.php';
			
			if (File::exists($replacerPath))
			{
				if ($template->path)
				{
					$replacerClass = '\\Cook\\Templates\\' . $this->templateName . '\\' . $template->path . '\\' . Str::title($template->name) . 'Replacer';
				}
				else
				{
					$replacerClass = '\\Cook\\Templates\\' . $this->templateName . '\\' . Str::title($template->name) . 'Replacer';
				}

				$template->replacerObject = new $replacerClass;
			}
		}
	}

	// Get tokens from each template, 
	// and set them to template token proterty as key.
	protected function findTokens()
	{
		foreach ($this->templates as $template) 
		{
			$template->tokens = $this->getTokens($template->content);
			$template->tabs = $this->getTabs($template->content, $template->tokens);
		}
	}
	
	// Get replacers for each template by invoking all replace methods, 
	// and set them to template token proterty as value.
	protected function findReplacers()
	{
		foreach ($this->templates as $i => $template) 
		{
			$template = $this->constructor->findReplacers($template);
		}
	}

	protected function setTemplatesPaths()
	{
		foreach ($this->templates as $i => $template) 
		{
			$template->path = substr(str_replace($this->root, '', $template->root), 1);
		}
	}

	// Get tokens from string
	private function getTokens($content)
	{
		$result = array();

		preg_match_all('#\<[A-z]+?\>#', $content, $result);

		return $result[0];
	}

	private function getTabs($content, $tokens)
	{
		$result = array();

		preg_match_all('#\t*\<[A-z]+?\>#', $content, $result);

		foreach ($result[0] as $i => $line) 
		{
			$result[0][$i] = strspn($line, "\t");
		}

		return $result[0];
	}

}