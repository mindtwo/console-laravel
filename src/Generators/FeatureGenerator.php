<?php

/*
 * This file is part of the lucid-console project.
 *
 * (c) Vinelab <dev@vinelab.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lucid\Console\Generators;

use Exception;
use Lucid\Console\Str;
use Lucid\Console\Components\Feature;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class FeatureGenerator extends Generator
{
    public function generate($feature, $service, array $jobs = [])
    {
        $feature = Str::feature($feature);
        $service = Str::service($service);

        $path = $this->findFeaturePath($service, $feature);

        if ($this->exists($path)) {
            throw new Exception('Feature already exists!');

            return false;
        }

        $featureSegments = explode('/', $feature);
        $feature = array_pop($featureSegments);
        $subfolderNameSpace = implode('\\', $featureSegments);

        $namespace = $this->findFeatureNamespace($service) . ($subfolderNameSpace ? '\\' . $subfolderNameSpace : '');

        $content = file_get_contents($this->getStub());

        $useJobs = ''; // stores the `use` statements of the jobs
        $runJobs = ''; // stores the `$this->run` statements of the jobs

        foreach ($jobs as $index => $job) {
            $useJobs .= 'use '.$job['namespace'].'\\'.$job['className'].";\n";
            $runJobs .= "\t\t".'$this->run('.$job['className'].'::class);';

            // only add carriage returns when it's not the last job
            if ($index != count($jobs) - 1) {
                $runJobs .= "\n\n";
            }
        }

        $foundation_class = $this->config('lucid.namespaces.foundation_feature');

        $content = str_replace(
            ['{{feature}}', '{{namespace}}', '{{foundation_class}}', '{{use_jobs}}', '{{run_jobs}}'],
            [$feature, $namespace, $this->checkNamespaceAsSurfix($foundation_class, 'Feature'), $useJobs, $runJobs],
            $content
        );

        $this->createFile($path, $content);

        // generate test file
        $this->generateTestFile($feature, $service, $subfolderNameSpace);

        return new Feature(
            $feature,
            basename($path),
            $path,
            $this->relativeFromReal($path),
            ($service) ? $this->findService($service) : null,
            $content
        );
    }

    /**
     * Generate the test file.
     *
     * @param  string $feature
     * @param  string $service
     */
    private function generateTestFile($feature, $service, $subfolderNameSpace=null)
    {
        $content = file_get_contents($this->getTestStub());

        $namespace = $this->findFeatureTestNamespace($service) . ($subfolderNameSpace ? '\\' . $subfolderNameSpace : '');
        $featureNamespace = $this->findFeatureNamespace($service) . ($subfolderNameSpace ? '\\' . $subfolderNameSpace : '') ."\\$feature";
        $testClass = $feature.'Test';

        $content = str_replace(
            ['{{namespace}}', '{{testclass}}', '{{feature}}', '{{feature_namespace}}'],
            [$namespace, $testClass, mb_strtolower($feature), $featureNamespace],
            $content
        );

        $path = $this->findFeatureTestPath(
            $service,
            ($subfolderNameSpace ? str_replace('\\', '/', $subfolderNameSpace) . '/' : '').$testClass
        );

        $this->createFile($path, $content);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/feature.stub';
    }

    /**
     * Get the test stub file for the generator.
     *
     * @return string
     */
    private function getTestStub()
    {
        return __DIR__.'/stubs/feature-test.stub';
    }
}
