# Entries Menu Manager
ExpressionEngine add-on to rearrange the Entries Menu (Channel list) in the CMS. Compatible with EE6 and EE7.

## Installation

This will be available in the ExpressionEngine add-on store soon. Until then, a .zip file of this repository can be downloaded directly from GitHub.

<img width="404" alt="image" src="https://github.com/jamf/entries-menu-manager/assets/2423727/2f327621-bfbe-478f-b3bf-af659dd5e0f4">

Unzip the file into the `/system/user/addons/` directory. The directory may need to be renamed to `entries_menu_manager`.

Go to the admin panel > Add-ons, and then click **Install** next to **Entries Menu Manager**.

<img width="388" alt="image" src="https://github.com/jamf/entries-menu-manager/assets/2423727/ccac054d-7d1c-40bb-b644-0730ee8d44df">

## Usage

To manage the order of the Entries menu, either click the Entries Menu Manager add-on or click the gear icon > Settings.

<img width="492" alt="image" src="https://github.com/jamf/entries-menu-manager/assets/2423727/207d18a8-b547-492f-a66f-4e74592bef3e">

Either of these paths will load the Settings page, where the Channel order for the Entries menu can be restructured into 1-3 columns, with optional nesting and Titles to further organize the channels.

Click and drag to rearrange Channels/Titles or to nest Channels, and click **Save** to save that order for all CMS users. JavaScript will run on each page load to rearrange the Entries menu based on the saved order. To revert back to the default Channel order (Alphabetical), click the **Reset to default layout** button, and the saved order will be reset.

<img width="1237" alt="image" src="https://github.com/jamf/entries_menu_manager/assets/2423727/6416d66c-37dc-4eae-92c4-2e0a4054eaa0">

<img width="422" alt="image" src="https://github.com/jamf/entries_menu_manager/assets/2423727/56beab13-1e81-4bbb-8851-9376f17df5de">

## Features

* Channels can be reorganized into 1-3 columns.
* Channels can be rearranged in a custom order. They must be either root-level or nested one level under another Channel or under a Title.
* Custom titles can be added to output text in the Entries menu. Titles must be root-level, must not be blank, and must have at least one child Channel.
* If a user does not have access to a given channel, the nesting will automatically resolve itself in the Entries menu.
  * *i.e. if they do not have access to a parent channel, then any child channels become root nodes. And if they do not have access to all child channels of a Title, then the Title is hidden too.*
* If new channels are added to the CMS, they are automatically added to the bottom of the first column. They can then be rearranged via the add-on Settings.
* If existing channels are removed from the CMS, they will be removed from the Entries menu as expected. Nesting will automatically resolve itself, depending on any parent/children that Channel had.

## Contributing

PRs are welcome to add new features or fix bugs. Approvals are required by the @jamf/dotcom-maintainer team. You can also open an Issue for features that warrant further discussion.
