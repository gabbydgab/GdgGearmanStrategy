<?php

/* * 
 * Copyright (c) 2014, Gab Amba <gamba@gabbydgab.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice,
 *   this list of conditions and the following disclaimer.
 *   
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *   
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * GdgGearmanTest\Controller\GearmanWorkerTest
 *
 * @author Gab Amba <gamba@gabbydgab.com>
 */

namespace GdgGearmanTest\Controller;

use GdgGearmanDemo\Sample\Controller\SampleGearmanWorker;

class GearmanWorkerTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->controller = new SampleGearmanWorker();
        
        $this->strategy = $this->getMockBuilder("GdgGearman\Worker\Strategy\AbstractWorkerStrategy")
                ->getMockForAbstractClass();
        
        $this->adapter = $this->getMockBuilder("GdgGearman\Worker\Adapter\WorkerStrategyAdapter")
            ->setConstructorArgs([$this->strategy])
            ->getMock();
    }
    
    /**
     * @test
     */
    public function settingFunctionName()
    {
        $name = "sample";
        
        $this->controller->setFunctionName($name);
        
        $this->assertEquals(
            $name,
            $this->controller->getFunctionName(),
            "GearmanWorker function_name mismatch"
        );
    }
    
    /**
     * @test
     */
    public function settingAdapter()
    {   
        $this->controller->setAdapter($this->adapter);
        
        $this->assertEquals(
            $this->adapter,
            $this->controller->getAdapter(),
            "GdgGearman\Worker\Adapter\AbstractWorkerStrategy object mismatch"
        );
    }
}
