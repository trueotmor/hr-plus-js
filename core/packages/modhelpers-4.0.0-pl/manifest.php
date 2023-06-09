<?php return array (
  'manifest-version' => '1.1',
  'manifest-attributes' => 
  array (
    'changelog' => 'Changelog for modHelpers.

4.0.0-pl
==============
- Added function "build_tree".
- Added function "reading_time".
- Added function "sanitize_path".
- Added function "value".
- Removed function "faker".
- Refactored the dump function. Now you can pass multiple agruments.
- Added the ctx parameter for the login and logout functions.
- Fixed the attach function of the Mailer class (#2).

3.7.0-pl
==============
- New function "get_exec_args".

3.7.0-beta
==============
- Added new function "exec_bg_script".
- Added new function "timer".
- Added "subDomain" and "subDomains" methods to the Request class.
- Added "storeAsOriginal" method to the UploadedFile class.

3.6.0-beta
==============
- Added new function "tag_encode".
- Added new function "tag_decode".
- Added new function "array_is_assoc".
- Added new function "is_odd".
- Added new function "is_even".
- Added some methods to the Str class (used in the "string" function).
- Added "dd" and "dump" methods to the Collection class.
- Updated all composer dependencies.

3.5.1-beta
==============
- Improved compatibility with PHP 7.2.

3.5.0-beta
==============
- New function "string".
- New function "str_concat".
- Fixed the bug with the email function.

3.4.0-beta
==============
- Added new function "first".
- Added new function "optional".
- Added new function "html_attributes".
- Improved the css() and script() functions.

3.3.0-beta
==============
- Added the session manager.
- The "session" function called without arguments returns the session manager instead of $_SESSION.
- Added new function "dump".
- Added new function "dd".
- Added new function "has_parent".
- Calling "user(true)" returns the current user.
- Calling "resource(true)" returns the current resource.
- Fixed a bug with caching in the "snippet" function.
- Updated composer libraries.
- Renamed the system setting "modhelpers_responseManager" to "modhelpers_responseManagerClass".

3.2.1-beta
==============
- Changed syntax for file elements with the relative path.
- Fixed the filter method of the Request class.

3.2.0-beta
==============
- Added new function "response".
- Renamed files of the classes.
- In the functions chunk() and snippet() you can specify the name with a relative path (if you use file elements).
- Added file "config/config.php" to add custom system settings. Can be helpful in testing mode.

3.1.1-beta
==============
- Fixed broken compatibility with PHP 5.5.

3.1.0-beta
==============
- Added method "getCsrfToken" to the Request class.
- Added method "checkCsrfToken" to the Request class.
- Added method "isBot" to the Request class.
- Added "bot_user_agents" system setting.
- Added a new function "csrf_token".
- Added a new function "csrf_field".
- Added a new function "csrf_meta".
- Changed the priority of the library loading.

3.0.1-beta
==============
- Added a new function "echo_br".
- Fixed bug with loading the ModelColunm class.
- Removed debug info.
- Updated the Faker library.

3.0.0-beta
==============
- IMPORTANT! Added namespace "modHelpers" to the modHelpers\' classes.
- Requirement of the minimum PHP version is changed to 5.5.
- Added a new function "request".
- Added a new function "switch_context".
- Added a new method "object" to the Object class.
- Added a new method "parent" to the Object class.
- Added a new method "first" to the Query class.
- Added a new method "toString" to the Query class.
- Added a new method "remember" to the CacheManager class.
- All classes can be extended.

2.1.0-pl
==============
- Added function "is_tablet".
- Added function "is_desktop".
- Added function "app".
- Added function "filter_data".
- Added function "null_if".
- Added the "relogin" parameter to the "logout" function.
- Improve the "pls" function.
- Improve the "parse" function.

2.0.0-pl
==============
- IMPORTANT! Changed the signature of the session() function. To put data to the session use an array.
- Added function "session_pull".
- Added function "default_if".
- Added method "log" to the modHelpersMailer class. It can be helpful for testing.
- Added method "toArray" to the modHelpersMailer class.
- Added method "tpl" to the modHelpersMailer class which set a chunk for the email content.

1.4.0-beta
==============
- Added function "is_mobile".
- Added function "array_empty".
- Added function "array_notempty".
- Added functions "array_trim", "array_ltrim" and "array_rtrim".
- Added functions "explode_trim", "explode_ltrim" and "explode_rtrim".
- Added function "echo_nl".
- Added function "print_str".
- Added function "print_d".
- Added function "parse".
- Added function "str_between".
- Added function "str_limit".
- Added functions - "str_starts", "str_ends", "str_contains" and "str_match".
- Added the queue functionality to the mailer class.
- The log functions can output objects which have the "toArray" method.
- Code refactoring.

1.3.0-beta
==============
- Added function "login".
- Added function "logout".
- Added function "is_ajax".
- Fixed some bugs.

1.2.0-beta
==============
- Added function "load_model" for a custom table or base objects.
- Added method "joinGroup" to the collection class for users and resources.
- Added method "leaveGroup" to the collection class for users and resources.
- Added method "whereIn" to the collection class.
- Added method "whereNotIn" to the collection class.
- Added method "whereLike" to the collection class.
- Added method "whereNotLike" to the collection class.
- Added method "whereIsNull" to the collection class.
- Added method "whereIsNotNull" to the collection class.
- Added class modHelpersMailer which allows to use chains of methods.
- Removed the functions "pdotools" and "pdofetch".

1.1.0-beta
==============
- Added function "faker" which generates fake data.
- Added function "img".
- Added method "whereExists" to the collection class.
- Added method "whereNotExists" to the collection class.
- Added method "elements" to the collection class.

1.0.2-beta
==============
- Renamed "esc" function to "escape".
- Added "memory" function.
- Added "forward" function.
- Added "orWhere" method to the collection class.
- Added "each" method to the collection class.
- Added "union" method to the collection class.
- Added "members" method to the collection class.
- Added an ability to pass a Closure to the collection functions.


1.0.1-beta
==============
- Renamed the log functions.
- Added "withTV" method to the collection class.

1.0.0-beta
==============
- Initial release.',
    'license' => 'GNU GENERAL PUBLIC LICENSE
   Version 2, June 1991
--------------------------

Copyright (C) 1989, 1991 Free Software Foundation, Inc.
59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.

Preamble
--------

  The licenses for most software are designed to take away your
freedom to share and change it.  By contrast, the GNU General Public
License is intended to guarantee your freedom to share and change free
software--to make sure the software is free for all its users.  This
General Public License applies to most of the Free Software
Foundation\'s software and to any other program whose authors commit to
using it.  (Some other Free Software Foundation software is covered by
the GNU Library General Public License instead.)  You can apply it to
your programs, too.

  When we speak of free software, we are referring to freedom, not
price.  Our General Public Licenses are designed to make sure that you
have the freedom to distribute copies of free software (and charge for
this service if you wish), that you receive source code or can get it
if you want it, that you can change the software or use pieces of it
in new free programs; and that you know you can do these things.

  To protect your rights, we need to make restrictions that forbid
anyone to deny you these rights or to ask you to surrender the rights.
These restrictions translate to certain responsibilities for you if you
distribute copies of the software, or if you modify it.

  For example, if you distribute copies of such a program, whether
gratis or for a fee, you must give the recipients all the rights that
you have.  You must make sure that they, too, receive or can get the
source code.  And you must show them these terms so they know their
rights.

  We protect your rights with two steps: (1) copyright the software, and
(2) offer you this license which gives you legal permission to copy,
distribute and/or modify the software.

  Also, for each author\'s protection and ours, we want to make certain
that everyone understands that there is no warranty for this free
software.  If the software is modified by someone else and passed on, we
want its recipients to know that what they have is not the original, so
that any problems introduced by others will not reflect on the original
authors\' reputations.

  Finally, any free program is threatened constantly by software
patents.  We wish to avoid the danger that redistributors of a free
program will individually obtain patent licenses, in effect making the
program proprietary.  To prevent this, we have made it clear that any
patent must be licensed for everyone\'s free use or not licensed at all.

  The precise terms and conditions for copying, distribution and
modification follow.


GNU GENERAL PUBLIC LICENSE
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
---------------------------------------------------------------

  0. This License applies to any program or other work which contains
a notice placed by the copyright holder saying it may be distributed
under the terms of this General Public License.  The "Program", below,
refers to any such program or work, and a "work based on the Program"
means either the Program or any derivative work under copyright law:
that is to say, a work containing the Program or a portion of it,
either verbatim or with modifications and/or translated into another
language.  (Hereinafter, translation is included without limitation in
the term "modification".)  Each licensee is addressed as "you".

Activities other than copying, distribution and modification are not
covered by this License; they are outside its scope.  The act of
running the Program is not restricted, and the output from the Program
is covered only if its contents constitute a work based on the
Program (independent of having been made by running the Program).
Whether that is true depends on what the Program does.

  1. You may copy and distribute verbatim copies of the Program\'s
source code as you receive it, in any medium, provided that you
conspicuously and appropriately publish on each copy an appropriate
copyright notice and disclaimer of warranty; keep intact all the
notices that refer to this License and to the absence of any warranty;
and give any other recipients of the Program a copy of this License
along with the Program.

You may charge a fee for the physical act of transferring a copy, and
you may at your option offer warranty protection in exchange for a fee.

  2. You may modify your copy or copies of the Program or any portion
of it, thus forming a work based on the Program, and copy and
distribute such modifications or work under the terms of Section 1
above, provided that you also meet all of these conditions:

    a) You must cause the modified files to carry prominent notices
    stating that you changed the files and the date of any change.

    b) You must cause any work that you distribute or publish, that in
    whole or in part contains or is derived from the Program or any
    part thereof, to be licensed as a whole at no charge to all third
    parties under the terms of this License.

    c) If the modified program normally reads commands interactively
    when run, you must cause it, when started running for such
    interactive use in the most ordinary way, to print or display an
    announcement including an appropriate copyright notice and a
    notice that there is no warranty (or else, saying that you provide
    a warranty) and that users may redistribute the program under
    these conditions, and telling the user how to view a copy of this
    License.  (Exception: if the Program itself is interactive but
    does not normally print such an announcement, your work based on
    the Program is not required to print an announcement.)

These requirements apply to the modified work as a whole.  If
identifiable sections of that work are not derived from the Program,
and can be reasonably considered independent and separate works in
themselves, then this License, and its terms, do not apply to those
sections when you distribute them as separate works.  But when you
distribute the same sections as part of a whole which is a work based
on the Program, the distribution of the whole must be on the terms of
this License, whose permissions for other licensees extend to the
entire whole, and thus to each and every part regardless of who wrote it.

Thus, it is not the intent of this section to claim rights or contest
your rights to work written entirely by you; rather, the intent is to
exercise the right to control the distribution of derivative or
collective works based on the Program.

In addition, mere aggregation of another work not based on the Program
with the Program (or with a work based on the Program) on a volume of
a storage or distribution medium does not bring the other work under
the scope of this License.

  3. You may copy and distribute the Program (or a work based on it,
under Section 2) in object code or executable form under the terms of
Sections 1 and 2 above provided that you also do one of the following:

    a) Accompany it with the complete corresponding machine-readable
    source code, which must be distributed under the terms of Sections
    1 and 2 above on a medium customarily used for software interchange; or,

    b) Accompany it with a written offer, valid for at least three
    years, to give any third party, for a charge no more than your
    cost of physically performing source distribution, a complete
    machine-readable copy of the corresponding source code, to be
    distributed under the terms of Sections 1 and 2 above on a medium
    customarily used for software interchange; or,

    c) Accompany it with the information you received as to the offer
    to distribute corresponding source code.  (This alternative is
    allowed only for noncommercial distribution and only if you
    received the program in object code or executable form with such
    an offer, in accord with Subsection b above.)

The source code for a work means the preferred form of the work for
making modifications to it.  For an executable work, complete source
code means all the source code for all modules it contains, plus any
associated interface definition files, plus the scripts used to
control compilation and installation of the executable.  However, as a
special exception, the source code distributed need not include
anything that is normally distributed (in either source or binary
form) with the major components (compiler, kernel, and so on) of the
operating system on which the executable runs, unless that component
itself accompanies the executable.

If distribution of executable or object code is made by offering
access to copy from a designated place, then offering equivalent
access to copy the source code from the same place counts as
distribution of the source code, even though third parties are not
compelled to copy the source along with the object code.

  4. You may not copy, modify, sublicense, or distribute the Program
except as expressly provided under this License.  Any attempt
otherwise to copy, modify, sublicense or distribute the Program is
void, and will automatically terminate your rights under this License.
However, parties who have received copies, or rights, from you under
this License will not have their licenses terminated so long as such
parties remain in full compliance.

  5. You are not required to accept this License, since you have not
signed it.  However, nothing else grants you permission to modify or
distribute the Program or its derivative works.  These actions are
prohibited by law if you do not accept this License.  Therefore, by
modifying or distributing the Program (or any work based on the
Program), you indicate your acceptance of this License to do so, and
all its terms and conditions for copying, distributing or modifying
the Program or works based on it.

  6. Each time you redistribute the Program (or any work based on the
Program), the recipient automatically receives a license from the
original licensor to copy, distribute or modify the Program subject to
these terms and conditions.  You may not impose any further
restrictions on the recipients\' exercise of the rights granted herein.
You are not responsible for enforcing compliance by third parties to
this License.

  7. If, as a consequence of a court judgment or allegation of patent
infringement or for any other reason (not limited to patent issues),
conditions are imposed on you (whether by court order, agreement or
otherwise) that contradict the conditions of this License, they do not
excuse you from the conditions of this License.  If you cannot
distribute so as to satisfy simultaneously your obligations under this
License and any other pertinent obligations, then as a consequence you
may not distribute the Program at all.  For example, if a patent
license would not permit royalty-free redistribution of the Program by
all those who receive copies directly or indirectly through you, then
the only way you could satisfy both it and this License would be to
refrain entirely from distribution of the Program.

If any portion of this section is held invalid or unenforceable under
any particular circumstance, the balance of the section is intended to
apply and the section as a whole is intended to apply in other
circumstances.

It is not the purpose of this section to induce you to infringe any
patents or other property right claims or to contest validity of any
such claims; this section has the sole purpose of protecting the
integrity of the free software distribution system, which is
implemented by public license practices.  Many people have made
generous contributions to the wide range of software distributed
through that system in reliance on consistent application of that
system; it is up to the author/donor to decide if he or she is willing
to distribute software through any other system and a licensee cannot
impose that choice.

This section is intended to make thoroughly clear what is believed to
be a consequence of the rest of this License.

  8. If the distribution and/or use of the Program is restricted in
certain countries either by patents or by copyrighted interfaces, the
original copyright holder who places the Program under this License
may add an explicit geographical distribution limitation excluding
those countries, so that distribution is permitted only in or among
countries not thus excluded.  In such case, this License incorporates
the limitation as if written in the body of this License.

  9. The Free Software Foundation may publish revised and/or new versions
of the General Public License from time to time.  Such new versions will
be similar in spirit to the present version, but may differ in detail to
address new problems or concerns.

Each version is given a distinguishing version number.  If the Program
specifies a version number of this License which applies to it and "any
later version", you have the option of following the terms and conditions
either of that version or of any later version published by the Free
Software Foundation.  If the Program does not specify a version number of
this License, you may choose any version ever published by the Free Software
Foundation.

  10. If you wish to incorporate parts of the Program into other free
programs whose distribution conditions are different, write to the author
to ask for permission.  For software which is copyrighted by the Free
Software Foundation, write to the Free Software Foundation; we sometimes
make exceptions for this.  Our decision will be guided by the two goals
of preserving the free status of all derivatives of our free software and
of promoting the sharing and reuse of software generally.

NO WARRANTY
-----------

  11. BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY
FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW.  EXCEPT WHEN
OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES
PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED
OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.  THE ENTIRE RISK AS
TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU.  SHOULD THE
PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING,
REPAIR OR CORRECTION.

  12. IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING
WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY AND/OR
REDISTRIBUTE THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES,
INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING
OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED
TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY
YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER
PROGRAMS), EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE
POSSIBILITY OF SUCH DAMAGES.

---------------------------
END OF TERMS AND CONDITIONS',
    'readme' => '--------------------
modHelpers
--------------------
Author: Sergey Shlokov <sergant210@bk.ru>
--------------------

Library of the helpfull functions for MODX.

Requirement:
PHP >= 5.5.

Feel free to suggest ideas/improvements/bugs on GitHub:
http://github.com/sergant210/modHelpers/issues',
    'setup-options' => 'modhelpers-4.0.0-pl/setup-options.php',
  ),
  'manifest-vehicles' => 
  array (
    0 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modNamespace',
      'guid' => '4238c7425383cdde704c72bde4545b5d',
      'native_key' => 'modhelpers',
      'filename' => 'modNamespace/8c6aae19814fc29a19d740ffa581407e.vehicle',
      'namespace' => 'modhelpers',
    ),
    1 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '949d3e5b27b320ec6a4d1d4bbc6bf434',
      'native_key' => 'modhelpers_print_template',
      'filename' => 'modSystemSetting/64d614a6cb5007811ae25828daca3ea7.vehicle',
      'namespace' => 'modhelpers',
    ),
    2 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '483783de056610f7cb61afe9fba9eb45',
      'native_key' => 'modhelpers_bot_user_agents',
      'filename' => 'modSystemSetting/234fd4d5c8a9dd7102c0be38d9121ab9.vehicle',
      'namespace' => 'modhelpers',
    ),
    3 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '897df846eb86436469f57b73379080d3',
      'native_key' => 'modhelpers_token_ttl',
      'filename' => 'modSystemSetting/181fb6cfcb4968c0a09a325d4c39f6af.vehicle',
      'namespace' => 'modhelpers',
    ),
    4 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'fbb6f5bdb753c96e139be2c85464a7e1',
      'native_key' => 'modhelpers_chunks_path',
      'filename' => 'modSystemSetting/cef0b0633f36cbf3b1f4c8ebeb624e60.vehicle',
      'namespace' => 'modhelpers',
    ),
    5 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'e8a513fa26f0f84d1a18a4cd5c4b27d8',
      'native_key' => 'modhelpers_snippets_path',
      'filename' => 'modSystemSetting/9991bb85458a1671788af38bae24166c.vehicle',
      'namespace' => 'modhelpers',
    ),
    6 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '999a3cd8fdc7c71c5f49f3797470d9bb',
      'native_key' => 'modhelpers_cache_model',
      'filename' => 'modSystemSetting/50ece4f90c65918d499a41009d66dc65.vehicle',
      'namespace' => 'modhelpers',
    ),
    7 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modCategory',
      'guid' => '31b1b54a03fc534b5aab850ec3a64836',
      'native_key' => NULL,
      'filename' => 'modCategory/2454e6e688bb7957a1acd3725ffb8470.vehicle',
      'namespace' => 'modhelpers',
    ),
  ),
);