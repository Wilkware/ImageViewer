<?php

declare(strict_types=1);

/** General functions  */
require_once __DIR__ . '/../libs/_traits.php';

/**
 * Class ImageViewer
 */
class ImageViewer extends IPSModuleStrict
{
    // -------------------------------------------------------------------------
    // Traits
    // -------------------------------------------------------------------------

    use DebugHelper;
    use FormatHelper;

    // Min IPS Object ID
    // private const IPS_MIN_ID = 10000;

    // -------------------------------------------------------------------------
    // Methods
    // -------------------------------------------------------------------------

    /**
     * In contrast to Construct, this function is called only once when creating the instance and starting IP-Symcon.
     * Therefore, status variables and module properties which the module requires permanently should be created here.
     *
     * @return void
     */
    public function Create(): void
    {
        //Never delete this line!
        parent::Create();

        // Video
        $this->RegisterPropertyString('ImageURL', 'https://');

        // Design
        $this->RegisterPropertyInteger('BackgroundColor', -1);
        $this->RegisterPropertyString('ImageFit', 'cover');

        // Advanced Settings
        $this->RegisterPropertyBoolean('AllowUpdate', false);
        $this->RegisterPropertyBoolean('AllowSnapshot', false);
        $this->RegisterPropertyInteger('SnapshotScript', 1);
        $this->RegisterPropertyInteger('SnapshotVariable', 1);

        // Set visualization type to 1, as we want to offer HTML
        $this->SetVisualizationType(1);
    }

    /**
     * This function is called when deleting the instance during operation and when updating via "Module Control".
     * The function is not called when exiting IP-Symcon.
     *
     * @return void
     */
    public function Destroy(): void
    {
        parent::Destroy();
    }

    /**
     * The content can be overwritten in order to transfer a self-created configuration page.
     * This way, content can be generated dynamically.
     * In this case, the "form.json" on the file system is completely ignored.
     *
     * @return string Content of the configuration page.
     */
    public function GetConfigurationForm(): string
    {
        // Get Form
        $form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

        // Extract Version
        $ins = IPS_GetInstance($this->InstanceID);
        $mod = IPS_GetModule($ins['ModuleInfo']['ModuleID']);
        $lib = IPS_GetLibrary($mod['LibraryID']);
        $form['actions'][1]['items'][2]['caption'] = sprintf('v%s.%d', $lib['Version'], $lib['Build']);

        // Debug output
        //$this->LogDebug(__FUNCTION__, $form);
        return json_encode($form);
    }

    /**
     * Is executed when "Apply" is pressed on the configuration page and immediately after the instance has been created.
     *
     * @return void
     */
    public function ApplyChanges(): void
    {
        // Never delete this line!
        parent::ApplyChanges();

        // Send a complete update message to the display, as parameters may have changed
        $this->UpdateVisualizationValue($this->GetFullUpdateMessage());

        // Set status
        $this->SetStatus(102);
    }

    /**
     * Is called when, for example, a button is clicked in the visualization.
     *
     * @param string $ident Ident of the variable
     * @param mixed $value The value to be set
     *
     * @return void
     */
    public function RequestAction(string $ident, mixed $value): void
    {
        // Debug output
        $this->LogDebug(__FUNCTION__, $ident . ' => ' . $value);
        // Ident == OnXxxxxYyyyy
        switch ($ident) {
            case 'SetImageUrl':
                $this->SetImageUrl($value);
                break;
            case 'Snapshot':
                if ($this->ReadPropertyBoolean('AllowSnapshot')) {
                    $script = $this->ReadPropertyInteger('SnapshotScript');
                    if (IPS_ScriptExists($script)) {
                        IPS_RunScriptWait($script);
                    }
                    $variable = $this->ReadPropertyInteger('SnapshotVariable');
                    if (IPS_VariableExists($variable)) {
                        if (HasAction($variable)) {
                            RequestAction($variable, $value);
                            IPS_Sleep(500);
                            $this->LogDebug(__FUNCTION__, 'RequestAction(' . $variable . ', ' . $value . ')');
                        }
                        else {
                            $this->LogDebug(__FUNCTION__, 'No action for variable: ' . $variable);
                        }
                    }
                }
                break;
        }
        // Send a complete update message to the display, as parameters may have changed
        $this->UpdateVisualizationValue($this->GetFullUpdateMessage());
    }

    /**
     * If the HTML-SDK is to be used, this function must be overwritten in order to return the HTML content.
     *
     * @return string Initial display of a representation via HTML SDK
     */
    public function GetVisualizationTile(): string
    {
        // Add a script to set the values when loading, analogous to changes at runtime
        // Although the return from GetFullUpdateMessage is already JSON-encoded, json_encode is still executed a second time
        // This adds quotation marks to the string and any quotation marks within it are escaped correctly
        $initialHandling = '<script>handleMessage(' . json_encode($this->GetFullUpdateMessage()) . ');</script>';
        // Add static HTML from file
        $module = file_get_contents(__DIR__ . '/module.html');
        // Check and add media object (background)
        // Important: $initialHandling at the end, as the handleMessage function is only defined in the HTML
        return $module . $initialHandling;
    }

    public function SetImageUrl(string $source): string
    {
        $url = '';
        if (!empty($source)) {
            if ($this->ReadPropertyBoolean('AllowUpdate')) {
                $url = $this->ReadPropertyString('ImageURL');
                IPS_SetProperty($this->InstanceID, 'ImageURL', $source);
                if (IPS_HasChanges($this->InstanceID)) {
                    IPS_ApplyChanges($this->InstanceID);
                }
            }
        }
        return $url;
    }

    /**
     * Generate a message that updates all elements in the HTML display.
     *
     * @return string JSON encoded message information
     */
    private function GetFullUpdateMessage(): string
    {
        // dataset variable
        $result = [
            'color'     => $this->GetColorFormatted($this->ReadPropertyInteger('BackgroundColor')),
            'fit'       => $this->ReadPropertyString('ImageFit'),
            'source'    => $this->ReadPropertyString('ImageURL'),
            'snapshot'  => $this->ReadPropertyBoolean('AllowSnapshot'),
        ];
        $this->LogDebug(__FUNCTION__, $result);
        return json_encode($result);
    }
}
