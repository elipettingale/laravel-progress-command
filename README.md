# Laravel Progress Command

A simple command which outputs progress to the console.

Will output two progress bars (passed and failed) which will be updated depending on the boolean return value of the fireItem() function.

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

