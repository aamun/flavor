<!--
 /* ===========================

  FlavorPHP - because php should have a better taste
  homepage: http://www.flavorphp.com/
  git repository: https://github.com/Axloters/FlavorPHP

  FlavorPHP is a free software licensed under the MIT license
  Copyright (C) 2008 by Pedro Santana <contacto at pedrosantana dot mx>
  
  Team:
  	Pedro Santana
	Victor Bracco
	Victor de la Rocha
	Jorge Condomí
	Aaron Munguia

  =========================== */
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
    	<title><?php echo $title_for_layout; ?></title>
		<meta name="generator" content="flavorPHP" />
        <?php echo $this->html->charsetTag("UTF-8"); ?>		
	</head>
    <body>
        <div>
            <?php echo $content_for_layout ?>
        </div>
    </body>
</html>