<?php


namespace App\Utils\Latte;


use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;
use Latte\Template;
use Nette\Environment;
use Nette\Utils\Random;

class UIMacros extends MacroSet
{

	private $localeUsed = FALSE;

	public static function install(Compiler $compiler)
	{
		$me = new self($compiler);
		$me->addMacro('ifLocale', array($me, 'macroLocale'), '} unset($_localeParam);');
		$me->addMacro('version', array($me, 'macroVersion'));
		$me->addMacro('phref', NULL, NULL, function (MacroNode $node, PhpWriter $writer) use ($me) {
			return ' ?> href="<?php ' . $me->macroLink($node, $writer) . ' ?>"<?php ';
		});

		return $me;
	}

	/************ macros *******************/

	/**
	 * {ifLocale cs,en}cs,en only{/ifLocale}
	 * n:ifLocale='cs'
	 */
	public function macroLocale(MacroNode $node, PhpWriter $writer)
	{
		$this->localeUsed = TRUE;
		$words = [];
		$words = $node->args;
		if (!$words) {
			throw new CompileException("Missing locale in n:{$node->name}.");
		}
		$node = $writer->formatWord($words);

		return $writer->write('$_localeParam = ' . $node . '; if($_localeParam === NULL || in_array($locale, (is_array($_localeParam) ? $_localeParam : (array) explode(",",$_localeParam)), TRUE)) {');
	}

	/**
	 * generates random number for front assets versing
	 */
	public function macroVersion(MacroNode $node, PhpWriter $writer)
	{
		$length = 10;
		$word = $node->tokenizer->fetchWord();
		if (is_numeric($word)) {
			$length = (int)$word;
		}

		return $writer->write(' ?>?' . Random::generate($length) . '<?php ');
	}

	/**
	 * n:phref="destination [,] [params]"
	 */
	public function macroLink(MacroNode $node, PhpWriter $writer)
	{
		$node->modifiers = preg_replace('#\|safeurl\s*(?=\||\z)#i', '', $node->modifiers);

		return $writer->using($node, $this->getCompiler())
			->write('echo %escape(%modify($_presenter->link(%node.word, %node.array?)))');
	}

	/**
	 */
	public function initialize()
	{
		$this->localeUsed = FALSE;
	}


	/**
	 * Finishes template parsing.
	 *
	 * @return array(prolog, epilog)
	 */
	public function finalize()
	{
		if (!$this->localeUsed) {
			return array();
		}

		return array(
			get_called_class() . '::validateTemplateParams($template);',
			NULL
		);
	}

	/**
	 * @param \Nette\Templating\Template $template
	 * @throws \Nette\InvalidStateException
	 */
	public static function validateTemplateParams(Template $template)
	{
		$params = $template->getParameters();
		if (!isset($params['locale'])) {
			$where = isset($params['control']) ?
				" of component " . get_class($params['control']) . '(' . $params['control']->getName() . ')'
				: NULL;

			throw new \Nette\InvalidStateException(
				'Please provide an active locale string ' .
				'as a parameter $locale to template' . $where
			);
		}
	}
}