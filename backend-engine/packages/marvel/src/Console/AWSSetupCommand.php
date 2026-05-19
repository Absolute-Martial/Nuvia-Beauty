<?php

namespace Marvel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Marvel\Traits\ENVSetupTrait;

use function Laravel\Prompts\{text, table, confirm, info, error};

class AWSSetupCommand extends Command
{
    use ENVSetupTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marvel:aws-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AIStor / S3-compatible storage setup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if the .env file exists
        $this->CheckENVExistOrNot();
        $reconfigure = '';

        try {
            do {
                // Read the current .env content
                $envFilePath = base_path('.env');
                $envContent = File::get($envFilePath);
                $targetKeys = ['MEDIA_DISK', 'FILESYSTEM_DISK', 'AISTOR_ACCESS_KEY_ID', 'AISTOR_SECRET_ACCESS_KEY', 'AISTOR_REGION', 'AISTOR_BUCKET', 'AISTOR_ENDPOINT', 'AISTOR_PUBLIC_URL', 'AISTOR_USE_PATH_STYLE_ENDPOINT', 'AISTOR_BUCKET_ENDPOINT', 'AISTOR_ROOT_PREFIX'];

                $data = $this->existingKeyValueInENV($targetKeys, $envContent);

                info('Please use arrow keys in keyboard for navigation.');
                if (confirm('Do you want to setup AIStor config?')) {
                    $media_disk = text(label: 'Enter media disk', default: $this->envValue($data, 0, 'public'));
                    $filesystem_disk = text(label: 'Enter filesystem disk', default: $this->envValue($data, 1, 'local'));
                    $aistor_access_key_id = text(label: 'Enter AIStor access key ID', default: $this->envValue($data, 2),  required: 'AIStor access key ID is required');
                    $aistor_secret_access_key = text(label: 'Enter AIStor secret key', default: $this->envValue($data, 3), required: 'AIStor secret key is required');
                    $aistor_region = text(label: 'Enter AIStor region', default: $this->envValue($data, 4, 'us-east-1'), required: 'AIStor region is required');
                    $aistor_bucket = text(label: 'Enter AIStor bucket', default: $this->envValue($data, 5), required: 'Bucket is required');
                    $aistor_endpoint = text(label: 'Enter AIStor endpoint', default: $this->envValue($data, 6));
                    $aistor_public_url = text(label: 'Enter public asset base URL (optional)', default: $this->envValue($data, 7));
                    $aistor_use_path_style_endpoint = text(label: 'Use path-style endpoint? (true/false)', default: $this->envValue($data, 8, 'false'));
                    $aistor_bucket_endpoint = text(label: 'Use bucket endpoint? (true/false)', default: $this->envValue($data, 9, 'false'));
                    $aistor_root_prefix = text(label: 'Enter bucket root prefix (optional)', default: $this->envValue($data, 10));

                    $this->awsTable(
                        $media_disk,
                        $filesystem_disk,
                        $aistor_access_key_id,
                        $aistor_secret_access_key,
                        $aistor_region,
                        $aistor_bucket,
                        $aistor_endpoint,
                        $aistor_public_url,
                        $aistor_use_path_style_endpoint,
                        $aistor_bucket_endpoint,
                        $aistor_root_prefix,
                    );
                    info('Do you want to update your AIStor configuration?');
                    info('If yes, your previous AIStor configuration will be removed permanently');
                    $confirmed = confirm(
                        label: "Are you sure!",
                        default: true,
                        yes: 'Yes, I accept',
                        no: 'No, I decline',
                        hint: 'The terms must be accepted to continue.'
                    );

                    if ($confirmed) {
                        $envContent = $this->awsDataSetup(
                            $envContent,
                            $media_disk,
                            $filesystem_disk,
                            $aistor_access_key_id,
                            $aistor_secret_access_key,
                            $aistor_region,
                            $aistor_bucket,
                            $aistor_endpoint,
                            $aistor_public_url,
                            $aistor_use_path_style_endpoint,
                            $aistor_bucket_endpoint,
                            $aistor_root_prefix,
                        );
                        File::put($envFilePath, $envContent);
                        info('Your AIStor configuration is updated successfully.');
                    } else {
                        info('Your previous data (if any) is kept.');
                    }
                }
                info('If you think there is something wrong in the config, then you can reconfigure it.');
                $reconfigure = confirm('Do you want to reconfigure AIStor settings?', false);

                // If the user wants to reconfigure, the loop will continue
            } while ($reconfigure);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function awsTable(
        $media_disk,
        $filesystem_disk,
        $aws_access_key_id,
        $aws_secret_access_key,
        $aws_default_region,
        $aws_bucket,
        $aws_endpoint,
        $aws_url,
        $aws_use_path_style_endpoint,
        $aws_bucket_endpoint,
        $aws_root_prefix
    ) {
        info('Please, check your credentials properly');
        table(['Key', 'Value'], [
            ['MEDIA_DISK', $media_disk],
            ['FILESYSTEM_DISK', $filesystem_disk],
            ['AISTOR_ACCESS_KEY_ID', $aws_access_key_id],
            ['AISTOR_SECRET_ACCESS_KEY', $aws_secret_access_key],
            ['AISTOR_REGION', $aws_default_region],
            ['AISTOR_BUCKET', $aws_bucket],
            ['AISTOR_ENDPOINT', $aws_endpoint],
            ['AISTOR_PUBLIC_URL', $aws_url],
            ['AISTOR_USE_PATH_STYLE_ENDPOINT', $aws_use_path_style_endpoint],
            ['AISTOR_BUCKET_ENDPOINT', $aws_bucket_endpoint],
            ['AISTOR_ROOT_PREFIX', $aws_root_prefix],
        ]);
    }

    // setup mailtrap's key and value in .env file
    private function awsDataSetup(
        $envContent,
        $media_disk,
        $filesystem_disk,
        $aws_access_key_id,
        $aws_secret_access_key,
        $aws_default_region,
        $aws_bucket,
        $aws_endpoint,
        $aws_url,
        $aws_use_path_style_endpoint,
        $aws_bucket_endpoint,
        $aws_root_prefix
    ) {
        $envContent = $this->setOrAppendEnvValue($envContent, 'MEDIA_DISK', $media_disk);
        $envContent = $this->setOrAppendEnvValue($envContent, 'FILESYSTEM_DISK', $filesystem_disk);
        $envContent = $this->setOrAppendEnvValue($envContent, 'AISTOR_ACCESS_KEY_ID', $aws_access_key_id);
        $envContent = $this->setOrAppendEnvValue($envContent, 'AISTOR_SECRET_ACCESS_KEY', $aws_secret_access_key);
        $envContent = $this->setOrAppendEnvValue($envContent, 'AISTOR_REGION', $aws_default_region);
        $envContent = $this->setOrAppendEnvValue($envContent, 'AISTOR_BUCKET', $aws_bucket);
        $envContent = $this->setOrAppendEnvValue($envContent, 'AISTOR_ENDPOINT', $aws_endpoint);
        $envContent = $this->setOrAppendEnvValue($envContent, 'AISTOR_PUBLIC_URL', $aws_url);
        $envContent = $this->setOrAppendEnvValue($envContent, 'AISTOR_USE_PATH_STYLE_ENDPOINT', $aws_use_path_style_endpoint);
        $envContent = $this->setOrAppendEnvValue($envContent, 'AISTOR_BUCKET_ENDPOINT', $aws_bucket_endpoint);
        $envContent = $this->setOrAppendEnvValue($envContent, 'AISTOR_ROOT_PREFIX', $aws_root_prefix);

        return $envContent;
    }

    private function envValue(array $data, int $index, string $fallback = ''): string
    {
        $value = $data[$index][1] ?? $fallback;

        return $value === 'Not found' ? $fallback : $value;
    }

    private function setOrAppendEnvValue(string $envContent, string $key, string $value): string
    {
        $pattern = "/^{$key}=.*$/m";

        if (preg_match($pattern, $envContent) === 1) {
            return (string) preg_replace($pattern, "{$key}={$value}", $envContent);
        }

        $separator = str_ends_with($envContent, PHP_EOL) ? '' : PHP_EOL;

        return $envContent.$separator."{$key}={$value}".PHP_EOL;
    }
}
