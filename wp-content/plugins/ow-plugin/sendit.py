#! /usr/bin/env python
''' Run this script on the command line to push out an update to all of the websites running this plugin '''

''' Dependencies:
This script relies on psutil, which you can pip install.
On linux distros you'll probably need to install the aditional dependency python3-devel before you can install psutil.
On fedora, this should probably work: `dnf install python3-devel`. For more information, and for other distros, see https://github.com/giampaolo/psutil/issues/1143
'''

''' The update process:
The website uses https://github.com/YahnisElsts/plugin-update-checker
to hook into WordPress to update this plugin from our Bitbucket repo.

The plugin does the following to update to <version-number>
1. Updates the version in the ow-plugin.php comments to <version-number>
2. commits and pushes the change
3. Adds a git tag
- `git tag <version-number>`: tags the last commit with version <version-number>
- `git push --tags`: pushes the tag (The normal `git push` ignores tags)

Also,
- `git tag`: Can be used when debugging to see the latest tags
- Because git tags are being used to determine when to update, they shouldn't be used for anything else.
'''

import os, sys, re, subprocess, psutil
from subprocess import call, STDOUT
from pathlib import Path

from pkg_resources import parse_version # Used when comparing pip versions, so everyone should have it even though it's not a built in module
import colorama # pip install colorama if you don't have it

root = Path(__file__).parent

def err(msg):
    sys.exit(colorama.Fore.RED + 'FAILURE: ' + msg + colorama.Style.RESET_ALL)

def get_plugin_version_from_code_comments(): # as opposed to from the git tag
    php = (root / 'ow-plugin.php').read_text()
    plugins_header_comment = php[php.find('/*') : php.find('*/')]
    for line in plugins_header_comment.split('\n'):
        if line.lower().startswith('version'):
            return line[line.find(':')+1:].strip()

def change_plugin_version_in_code_comments(new_version):
    php = (root / 'ow-plugin.php').read_text()
    new_php, replacements_count = re.subn(r'version:\s+[\d.]+\s*\n', f'Version: {new_version}\n', php, 1, re.IGNORECASE)
    if replacements_count:
        (root / 'ow-plugin.php').write_text(new_php)
        return True
    else:
        return False

def change_git_tag_version_locally(new_version):
    res = subprocess.run(["git", "tag", new_version], check=True, stderr=subprocess.PIPE).stderr
    if res:
        return False, 'There was an error when creating the git tag\n' + res
    return True, None

def git_commit(new_version):
    res = subprocess.run(["git", "add", "ow-plugin.php"], check=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT).stdout.decode("utf-8")
    if res:
        return False, 'Failed to stage ow-plugin.php'

    commit_msg = "Releasing version " + new_version + " to the world"
    res = subprocess.run(["git", "commit", "-m", commit_msg], stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    exit_code = res.returncode
    res = res.stdout.decode("utf-8")
    if exit_code != 0:
        return False, 'Commit failed\n' + colorama.Style.RESET_ALL + res
    if new_version not in res or 'error' in res.lower():
        return False, 'Failed to commit'
    return True, None

def git_push(new_version):
    res = subprocess.run(["git", "push"], check=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT).stdout.decode("utf-8")
    if 'error' in res.lower():
        return False, 'Failed to push'
    return True, None

def push_git_tag():
    res = subprocess.run(["git", "push", "--tags"], check=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT).stdout.decode("utf-8")
    if not ( "[new tag]" in res or "Everything up-to-date" in res ):
        return False, 'Failed to push git tag'
    return True, None



# Initialize color support
colorama.init()

# Get the current plugin version
version = get_plugin_version_from_code_comments()
if not version:
    err('Unable to find the current version number')

# Make sure we're in a git repo
if call(["git", "branch"], stderr=STDOUT, stdout=open(os.devnull, 'w')) != 0:
    err('This is not a git repo')

# Make sure our branch is up to date
res = subprocess.run(["git", "fetch", "--dry-run"], check=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT).stdout
if res:
    err('Your repo is not up to date. Please run `git fetch`')
res = subprocess.run(["git", "status", "-uno"], check=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT).stdout
status_txt = res.decode("utf-8")
if 'On branch master' not in status_txt:
    err('You must be on the master branch')
if "Your branch is ahead of 'origin/master' by" in status_txt:
    print(colorama.Fore.YELLOW + 'WARNING: ' + colorama.Style.RESET_ALL, end='' )
    yn = input(f'You have unpushed changes. If you continue, all of your committed changes will be pushed. Do you wish to push these changes and continue? [y/N]: ')
    if not yn.lower().startswith('y'):
        err('sendit was unsucessful')
elif "Your branch is up-to-date with 'origin/master'." not in status_txt and "Your branch is up to date with 'origin/master'." not in status_txt: # Older versions of git said up-to-date
    err('Your repo is outdated. Please run `git pull`')

# Make sure there are no staged changes
res = subprocess.run(["git", "diff", "--staged", "--name-only"], stdout=subprocess.PIPE, stderr=subprocess.STDOUT).stdout.decode("utf-8")
if res:
    err('You currently have staged changes. Please commit your changes, and then run the sendit.py script again')

# Ask for the new version
new_version = input(f'What version would you like to update to? Currently at version {version}: ').strip()
old_version = version
if not new_version:
    err('Did not receive a version number')
invalid_chars = re.sub('[\d\.]', '', new_version)
if invalid_chars:
    err('Only numbers and periods are allowed in the version number. Received the invalid character(s): ' + invalid_chars)

# Verify the version entered is later than the current version
if new_version is version:
    err("You're already using that version")
if parse_version(new_version) == parse_version(version):
    print(colorama.Fore.YELLOW + 'WARNING: ' + colorama.Style.RESET_ALL, end='' )
    yn = input(f'{new_version} appears to be the same as the currently installed version {version}. Do you wish to continue anyways? [y/N]: ')
    if not yn.lower().startswith('y'):
        err('sendit was unsucessful')
if parse_version(new_version) < parse_version(version):
    err('Error: You entered a version number that was less than the current version')

# Get the last version according to git tags
ps = subprocess.Popen(("git", "tag", "-l", "[0-9].*", "--sort=committerdate"), stdout=subprocess.PIPE) # The "[0-9].*" part ignores tags that start with words such as beta-
git_version = subprocess.check_output(('tail', '-n1'), stdin=ps.stdout, text=True).strip()
ps.wait()

# Verify the git tag version is valid
if not git_version:
    err('failed to get the last git tag')
if new_version is git_version:
    err(f"{new_version} is the same as the last git tag")
if parse_version(new_version) == parse_version(git_version):
    print(colorama.Fore.YELLOW + 'WARNING: ' + colorama.Style.RESET_ALL, end='' )
    yn = input(f'{new_version} appears to be the same as the last git tag entered {git_version}. Do you wish to continue anyways? [y/N]: ')
    if not yn.lower().startswith('y'):
        err('sendit was unsucessful')
if parse_version(new_version) < parse_version(git_version):
    err('Error: You entered a version number that was less than the current version')

# Change the version number in ow-plugin.php comments
success = change_plugin_version_in_code_comments(new_version)
if not success:
    err('failed to change version in ow-plugin.php comments')

# Commit (must do this before tagging, so the right commit gets tagged)
success, err_msg = git_commit(new_version)
if not success:
    err(err_msg)

# Tag the last commit with our new version number
success, err_msg = change_git_tag_version_locally(new_version)
if not success:
    # Attempt to rollback the changes that have been made when we encounter a problem
    if not change_plugin_version_in_code_comments(old_version):
        print(colorama.Fore.RED + 'FAILURE: ' + 'Failed to revert the plugin version. Please manually fix this in the comments of ow-plugin.php if it needs to be fixed' + colorama.Style.RESET_ALL)
    err(err_msg)

# Push our changes
success, err_msg = git_push(new_version)
if not success:
    err(err_msg)
success, err_msg = push_git_tag()
if not success:
    err(err_msg)

# Output success message
print('\nYour update has been made available for websites to download')
print(colorama.Fore.GREEN + 'Successfully updated ow-plugin' + colorama.Style.RESET_ALL)

# If a new shell window was opened to run this program, wait for the user to hit enter before closing it
running_from = psutil.Process(os.getpid()).parent().name()
if running_from == 'explorer.exe' or running_from == 'gnome-terminal':
    input()
