# StrictForm
a brand-new form API with strict typing.

## Examples
### Simple form (form with multiple buttons with text)
```php
use oirancage\strictform\SimpleForm;
use oirancage\strictform\component\Button;
use oirancage\strictform\component\UrlImage;
use oirancage\strictform\component\ResourcePackImage;
use oirancage\strictform\response\SimpleFormResponse;
use oirancage\strictform\component\exception\InvalidFormResponseException;

$form = SimpleForm::create(
    "Market",
    "Choose product to buy",
    [
        new Button("orange", "orange $2"),
        new Button("apple", "apple $3", new ResourcePackImage("texture/items/apple")),
        new Button("banana", "banana $5", new UrlImage("https://example.com/banana.png")),
    ]
)->onSuccess(function(SimpleFormResponse $response) : void{
    $from = $response->getFrom();
    $value = $response->getSelectedButtonValue();
    /**
     * when you press button with 'apple $3', then value will be 'apple', the name of the button.
     */
    $from->sendMessage("You choose $value!");
})->onClose(function(Player $from) : void{
    $from->sendMessage("You choose nothing and closed form.");
})->onValidationError(function(Player $from, InvalidFormResponseException $exception) : void{
    $from->sendMessage("Error: {$exception->getMessage()}");
});
```

### Custom form
```php
use oirancage\strictform\CustomForm;
use oirancage\strictform\component\Input;
use oirancage\strictform\component\Slider;
use oirancage\strictform\component\Dropdown;
use oirancage\strictform\component\StringEnumOption;
use oirancage\strictform\component\StepSlider;
use oirancage\strictform\component\Toggle;
use oirancage\strictform\response\CustomFormResponse;
use oirancage\strictform\component\exception\InvalidFormResponseException;

$form = CustomForm::create(
    "Submission",
    [
        new Input("name", "Name", "famima65536"),
        new Slider("age", "Age", 0, 100, 1),
        new Dropdown("gender", "Gender", [
            new StringEnumOption("male", "Male"),
            new StringEnumOption("female", "Female")
        ], 0),
        new StepSlider("skill-level", "Programming Skill", [
            new StringEnumOption("beginner", "Beginner"),
            new StringEnumOption("intermediate", "Intermediate"),
            new StringEnumOption("advanced", "Advanced")
        ]),
        new Toggle("some-toggle", "toggle")
    ]
)->onSuccess(function(CustomFormResponse $response) : void{
    $from = $response->getFrom();
    $name   = $response->getInputValue("name");
    $age    = (int) $response->getSliderValue("age");
    $gender = $response->getDropdownValue("gender");
    $level  = $response->getStepSliderValue("skill-level");
    $toggle = $response->getToggleValue("some-toggle");
    $content = <<<content
        name   ${name}
        age    ${age}
        gender ${gender}
        skill  ${level}
        toggle ${toggle}
        content;
    $from->sendMessage("You sent:");
    $from->sendMessage($content);
})->onClose(function(Player $from) : void{
    $from->sendMessage("You choose nothing and closed form.");
})->onValidationError(function(Player $from, InvalidFormResponseException $exception) : void{
    $from->sendMessage("Error: {$exception->getMessage()}");
});
```

### Modal form(Yes or No)
```php
use oirancage\strictform\ModalForm;
use oirancage\strictform\response\ModalFormResponse;
use oirancage\strictform\component\exception\InvalidFormResponseException;

$form = ModalForm::create(
    "Question",
    "Are you pocketmine-mp plugin developer?",
    "Yes",
    "No"
)->onSuccess(function(ModalFormResponse $response) : void{
    $from = $response->getFrom();
    $value = $response->getSelectedValue();
    $message = match($value){
        true => "Tell me your plugin repository.",
        false => "Tell me your favorite plugin",
    }
    $from->sendMessage($message);
})->onClose(function(Player $from) : void{
    $from->sendMessage("You choose nothing and closed form.");
})->onValidationError(function(Player $from, InvalidFormResponseException $exception) : void{
    $from->sendMessage("Error: {$exception->getMessage()}");
});
```