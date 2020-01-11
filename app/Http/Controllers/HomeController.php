<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $colors = array('blue', 'green', 'orange', 'red', 'yellow');
    private $startMarker = 'blue';
    private $endMarker = 'green';
    protected $tiles = [];

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // number of tiles to generate, default to 3
        $tileOptions = (int) $request->get('tiles', 3);
        $this->generateTiles($tileOptions);
        $tiles = $this->getTiles();

        // find iterations for a possible solution
        $solution = $this->analyzeTiles($this->tiles);
        return view('home', compact('tiles', 'solution'));
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $tiles = [];
        $solution = [];

        if (request('tiles')) {
            $tileEntries = request('tiles');
            $this->processEntries($tileEntries);
        }

        $tiles = $this->getTiles();

        // find iterations for a possible solution
        $solution = $this->analyzeTiles($this->tiles);
        return view('home', compact('tiles', 'solution'));
    }

    /**
     * Processs and format manual color entries
     *
     * @param array $entries
     * @return void
     */
    private function processEntries(array $entries)
    {
        foreach ($entries as $entry) {
            $colors = array_map('trim', explode(',', $entry));
            if (is_array($colors) && (count($colors) === 2)) {
                $this->tileDisplay[] = $colors;
                $this->tiles[$colors[0]][] = $colors[1];
            }
        }
    }

    /**
     * Retrieve all the possible tiles
     *
     * @return array
     */
    private function getTiles(): array
    {
        if (isset($this->tileDisplay) && !empty($this->tileDisplay)) {
            return $this->tileDisplay;
        }

        return [];
    }

    /**
     * Generate tiles based on the number passed to the get parameter
     *
     * @param integer $numberOfTiles
     * @return void
     */
    private function generateTiles(int $numberOfTiles)
    {
        for ($i = 0; $i < $numberOfTiles; $i++) {
            $this->setRandomTiles();
        }

        return;
    }

    /**
     * Set all of the generated tiles as a reference
     *
     * @return void
     */
    private function setRandomTiles()
    {
        $colors = $this->colors;
        shuffle($colors);
        $color = array_pop($colors);
        $secondaryColor = array_pop($colors);
        $this->tiles[$color][] = $secondaryColor;
        $this->tileDisplay[] = array($color, $secondaryColor);

        return;
    }

    /**
     * Analyze all of the tiles and create and return the best solution
     *
     * @param array $collection
     * @return array contains the best solution for solving the lock
     */
    public function analyzeTiles(array $collection): array
    {
        if (!isset($collection[$this->startMarker]) || empty($collection[$this->startMarker])) {
            return [];
        }

        $startCount = count($collection[$this->startMarker]);
        for ($i = 0; $i < $startCount; $i++) {
            // All current primary and their associated secondary color
            $tempOptions = $collection;
            $this->thread[$i][] = array($this->startMarker, $tempOptions[$this->startMarker][$i]);

            // remove the selected option from the current list of future options
            unset($tempOptions[$this->startMarker][$i]);
            $this->options[] = $tempOptions;
        }

        $i = 0;
        // cycle through each of the possible start blue patterns
        foreach ($this->thread as $thread) {
            $this->processNextConnection($i, $this->options[$i]);
            $i++;
        }

        return $this->compileSolution();
    }

    /**
     * this cycles through all of the current options and find the next possible connection
     * until the end marker color has been found
     *
     * @param integer $index
     * @param array $options
     * @return void
     */
    private function processNextConnection(int $index, array $options = [])
    {
        // get the last color in the thread that needs to be matched
        if (!isset($this->thread[$index][(count($this->thread[$index]) - 1)])) {
            return false;
        }
        $needle = $this->thread[$index][(count($this->thread[$index]) - 1)];

        if (is_array($needle) && !empty($needle)) {
            $needle = strtolower($needle[(count($needle)) - 1]);
        }

        if (strtolower($needle) === $this->endMarker) {
            return true;
        }

        if (isset($options[$needle]) && !empty($options[$needle])) {
            $startCount = count($options[$needle]);
            for ($i = 0; $i < $startCount; $i++) {
                if (!isset($options[$needle][$i]) || empty($options[$needle][$i])) {
                    $removeDeadend = count($this->thread[$index]) - 1;
                    unset($this->thread[$index][$removeDeadend]);
                    // unset($needle);
                } else {
                    $this->thread[$index][] = [$needle, $options[$needle][$i]];
                    unset($options[$needle][$i]);
                }

                if ($this->processNextConnection($index, $options)) {
                    return true;
                }
            }
        }
        unset($needle);
        return false;
    }

    /**
     * Return the best solution for all the successful combinations
     *
     * @return array
     */
    private function compileSolution(): array
    {
        $solutionLength = 0;
        $solution = [];

        if (isset($this->thread)) {
            for ($i = 0; $i < count($this->thread); $i++) {
                $end = end($this->thread[$i]);
                if (!empty($end) && strtolower($end[1]) !== $this->endMarker) {
                    continue;
                }

                $threadLength = count($this->thread[$i]);
                if ($threadLength > $solutionLength) {
                    $solutionLength = $threadLength;
                    $solutionData = $this->thread[$i];
                }
            }
        }

        if (!empty($solutionData)) {
            foreach ($solutionData as $data) {
                $solution[] = array($data[0], $data[1]);
            }
        }

        return $solution;
    }
}
