# Jamf Entries Menu Manager
ExpressionEngine add-on to rearrange the Entries Menu (Channel list) in the CMS. Compatible with EE6 and EE7.

## Installation

A .zip file of this repository can be downloaded directly from GitHub.

<img width="412" alt="image" src="https://github.com/jamf/jamf_entries_menu_manager/assets/2423727/9e9850e2-d525-40a3-bdac-cbfc4312162d">

Unzip the file into the `/system/user/addons/` directory. The directory may need to be renamed to `jamf_entries_menu_manager`.

Go to the Control Panel > Add-ons, and then click **Install** next to **Jamf Entries Menu Manager**.

<img width="333" alt="image" src="https://github.com/jamf/jamf_entries_menu_manager/assets/2423727/93b0464c-63fd-4079-b168-a065c41c357f">

## Usage

To manage the order of the Entries menu, either click the add-on or click the gear icon and then click Settings to open the Settings page.

<img width="423" alt="image" src="https://github.com/jamf/jamf_entries_menu_manager/assets/2423727/c8d7c761-5953-4ca5-9c60-c3670e5fdaf7">

On the Settings page, the Channel order for the Entries menu can be restructured and additional organizational options such as nesting are available.

Click and drag to rearrange Channels/Titles or to nest Channels, and click **Save** to save that order for all CMS users. JavaScript will run on each Control Panel page load to rearrange the Entries menu based on the saved order. To revert back to the default Channel order (Alphabetical), click the **Reset to default layout** button, and the saved order will be reset.

<img width="1237" alt="image" src="https://github.com/jamf/entries_menu_manager/assets/2423727/6416d66c-37dc-4eae-92c4-2e0a4054eaa0">

<img width="422" alt="image" src="https://github.com/jamf/entries_menu_manager/assets/2423727/56beab13-1e81-4bbb-8851-9376f17df5de">

## Features

* Channels can be reorganized into 1-3 columns.
* Channels can be rearranged in a custom order. They must be either root-level or nested one level under another Channel or under a Title.
* Custom titles can be added to output text in the Entries menu. Titles must be root-level, have at least one Channel, and not be blank.
* If a user does not have access to a given channel, the nesting will automatically resolve itself in the Entries menu.
  * *i.e. If they do not have access to a parent channel, then any child channels become root nodes. If they do not have access to all child channels of a Title, then the entire Title is hidden.*
* If new channels are added to the CMS, they are automatically added to the bottom of the first column. They can then be rearranged via the add-on Settings.
* If existing channels are removed from the CMS, they will be removed from the Entries menu as expected. Nesting will automatically resolve itself, depending on any parent/children that Channel had.

## Contributing

PRs are welcome to add new features or fix bugs. Approvals are required by the @jamf/dotcom-maintainer team. You can also open an Issue for features that warrant further discussion.
