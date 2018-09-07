# Laravel Progress Command

A simple command which outputs progress bars to the console.

Will output various progress bars which will be updated depending on the key returned by the the fireItem() function.

## Example
    class TestCommand extends ProgressCommand implements HasInfoBar
    {
        protected $signature = 'test-command';
       
        protected function getItems()
        {
            return ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten'];
        }
        
        protected function fireItem($item): string
        {
            sleep(1);
            
            return array_random(['success', 'error']);
        }
        
        public function getItemIdentifier($item): string
        {
            return $item;
        }
        
        protected function getProgressBarBlueprints(): array
        {
            return [
                new ProgressBarBlueprint('success', 'Success', [
                    'foreground' => 'green'
                ]),
                new ProgressBarBlueprint('error', 'Error', [
                    'foreground' => 'red'
                ])
            ];
        }
    }

### ProgressBarBlueprint

These are how you define the progress bars that get printed to the console. Each blueprint must have a 'key', which is used to identify which progress bar to increment by the fireItem method, and a 'description' which is free text that is used as a label for the progress bar.
