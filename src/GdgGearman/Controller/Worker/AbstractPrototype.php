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
 * GdgGearman\Controller\Worker\AbstractPrototype
 *
 * @author Gab Amba <gamba@gabbydgab.com>
 * @package GdgGearman\Controller\Worker
 */

namespace GdgGearman\Controller\Worker;

use GdgGearman\Worker\Adapter\WorkerStrategyAdapter;
use GearmanJob;

abstract class AbstractPrototype implements AwareInterface
{
    private $_functionName = "";
    
    private $_callableFunction = "_doWork";
    
    /**
     * @var GearmanWorker
     */
    private $_worker;
    
    /**
     * @var \GdgGearman\Worker\Adapter\WorkerStrategyAdapter
     */
    private $_adapter;
    
    /**
     * Sets the name of a function to register with the job server
     * 
     * @param String $functionName
     */
    public function setFunctionName($functionName)
    {
        $this->_functionName = $functionName;
    }
    
    /**
     * Gets the name of a function to register with the job server
     * 
     * @return String $_functionName
     */
    public function getFunctionName()
    {
        return $this->_functionName;
    }
    
    /**
     * Adapter implementation for worker strategies
     * 
     * @param \GdgGearman\Worker\Adapter\WorkerStrategyAdapter $adapter
     */
    public function setAdapter(WorkerStrategyAdapter $adapter)
    {
        $this->_adapter = $adapter;
    }
    
    /**
     * 
     * @return \GdgGearman\Worker\Adapter\WorkerStrategyAdapter
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }
    
    public function setGearmanWorker(\GearmanWorker $worker)
    {
        $this->_worker = $worker;
    }
    
    public function getGearmanWorker()
    {
        return $this->_worker;
    }
    
    /**
     * @return void
     */
    public function performWorkAction()
    {
        $this->getGearmanWorker()->addFunction(
            $this->getFunctionName(),
            [$this, $this->_callableFunction]
        );
        
        while (true) {
            $this->getGearmanWorker()->work();            
            if ($this->getGearmanWorker()->returnCode() != "GEARMAN_SUCCESS") {
                break;
            }
        }
    }
    
    private function _doWork(\GearmanJob $job)
    {
        $this->_payload = [];
        $this->getAdapter()->init($this->_payload);
        
        return $this->getAdapter()->executeWork();
    }
}
