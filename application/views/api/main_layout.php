<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">
        <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon_1.ico');?>">
        <title>Second Screen</title>
        <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/css/core.css');?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/css/components.css');?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/css/api/styles.css');?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css"/>

        <?php
            if (isset($customCss) && is_array($customCss)){
                foreach($customCss as $style){
                   echo '<link href="' . base_url($style) . '" rel="stylesheet" type="text/css" />' . "\n";
                }
            }
        ?>
        
    </head>


    <body>
        <div class="content">
            <div class="container">
                <?php 
                  echo isset($content) ? $content : 'Empty page';   
                ?>
            </div>
        </div>
        
        <?php          
            if (isset($customJs) && is_array($customJs)){
                foreach($customJs as $script){
                   echo '<script type="text/javascript" src="' . base_url(). $script . '"></script>' . "\n";
                }
            }
        ?>
        
    </body>
</html>