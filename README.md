# platesphpSlotsExtension
platesphpSlotsExtension

Extension for    
https://platesphp.com/    
https://github.com/thephpleague/plates    

## Usage
In template generated by `$this->component` method.

components/header.php
```php
 <header>
    <nav>
        <?= $slot("nav") ?>
    </nav>
    <?php $this->component("components::dropdown") ?>
        <?= $slot(/*no arg => default slot*/) /* pass slot into slot */ ?>
    <?php $this->endcomponent() ?>
 </header>
```

view/index.php
```php
 <body>
    <?php $this->component("components::header"); ?>
        <?php $this->startSlot("nav"); ?>
            hello, I am in nav element
        <?php $this->stopSlot(); ?>
        hello, I am default slot passed to the dropdown component inside header component
    <?php $this->endcomponent(); ?>
 </body>
```

### checking if slot exists
```php
 <header>
    <nav>
        <?php if($slot("nav")): ?>
           <?= $slot("nav") ?>
        <?php else: ?>
           default nav slot
        <?php endif; ?>
    </nav>
    <!-- you don't have to use the default slot -->
 </header>
```
