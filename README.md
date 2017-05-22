# YCHANGE Elgg Plugin

This is a plugin for [Elgg](https://elgg.org/) version 2.x that add all the
functionalities required by the YCHANGE project.

## Installation

Please [download](https://elgg.org/about/download) and install the suitable
version of [Elgg](https://elgg.org/). Then place the plugin into the `mod`
directory and activate it.

NB! Please make sure that the name of the directory is the same as `id`
element value of the `manifest.xml` file.

```
If you have accidentally placed it under different directory name and it does
not show up in the plugins list, then it has been added to the database with the
wrong name. Please open the elgg_objects_entity (the beginning depends on the
prefix_ value chosen, if any) table and fix the name to correspond to the
required one. Example: YCHANGE VS ychange.
```
