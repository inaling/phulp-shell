<?php

describe('Phulp\Plugin\Shell', function () {
    beforeEach(function () {
        $this->shell = new \Phulp\Plugin\Shell();
        $this->assert = new \Peridot\Leo\Interfaces\Assert();
    });

    describe('->createCommand()', function () {
        it('no bashrc, no cwd', function () {
            unset($this->shell->config['phulp_bashrc_path']);
            $input = 'echo no bashrc';
            $cwd = exec('echo $PWD');
            $command = [
                'command' => $input
            ];
            $result = $this->shell->createCommand($command);
            $this->assert->equal($result['command'], $input);
            $this->assert->notinclude($result['command'], 'expand_aliases');
            $this->assert->equal($result['cwd'], $cwd);
        });

        it('yes bashrc, no cwd', function () {
            $this->shell->config['phulp_bashrc_path'] = exec('echo $HOME/.bashrc');
            $input = 'echo yes bashrc';
            $command = [
                'command' => $input
            ];
            $result = $this->shell->createCommand($command);
            $this->assert->include($result['command'], $input);
            $this->assert->include($result['command'], 'expand_aliases');
            $this->assert->include($result['command'], $this->shell->config['phulp_bashrc_path']);
        });

        it('no bashrc, yes cwd', function () {
            unset($this->shell->config['phulp_bashrc_path']);
            $input = 'echo yes cwd';
            $cwd = '$PWD/test';
            $command = [
                'command' => $input,
                'cwd' => $cwd
            ];
            $cwd = exec("echo {$cwd}");
            $result = $this->shell->createCommand($command);
            $this->assert->equal($result['command'], $input);
            $this->assert->notinclude($result['command'], 'expand_aliases');
            $this->assert->equal($result['cwd'], $cwd);
        });
    });
});
