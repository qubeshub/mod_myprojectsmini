<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Alissa Nedossekina <alisa@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// no direct access
defined('_HZEXEC_') or die();

// Push the module CSS to the template
$this->css();

$projects = $this->rows;

$setup_complete = $this->pconfig->get('confirm_step', 0) ? 3 : 2;
?>
<div<?php echo ($this->moduleclass) ? ' class="' . $this->moduleclass . '"' : ''; ?> id="myprojects">
	<?php if ($this->params->get('button_show_all', 1) || $this->params->get('button_show_add', 1)) { ?>
	<ul class="module-nav">
		<?php if ($this->params->get('button_show_all', 1)) { ?>
		<li>
			<a class="icon-browse" href="<?php echo Route::url('index.php?option=com_projects&task=browse'); ?>">
				<?php echo Lang::txt('MOD_MYPROJECTS_ALL_PROJECTS'); ?>
			</a>
		</li>
		<?php } ?>
		<?php if ($this->params->get('button_show_add', 1)) { ?>
		<li>
			<a class="icon-plus" href="<?php echo Route::url('index.php?option=com_projects&task=start'); ?>">
				<?php echo Lang::txt('MOD_MYPROJECTS_NEW_PROJECT'); ?>
			</a>
		</li>
		<?php } ?>
	</ul>
	<?php } ?>

	<?php if ($projects && $this->total > 0) { ?>
		<ul class="compactlist">
			<?php
			$i = 0;
			foreach ($projects as $row)
			{
				if ($i >= $this->limit)
				{
					break;
				}
				$goto  = 'alias=' . $row->alias;
				$owned_by = Lang::txt('MOD_MYPROJECTS_BY') . ' ';
				if ($row->owned_by_group)
				{
					$owned_by .= '<strong>' . \Hubzero\Utility\String::truncate($row->groupname, 20) . '</strong>';
				}
				else if ($row->created_by_user == User::get('id'))
				{
					$owned_by .= Lang::txt('MOD_MYPROJECTS_ME');
				}
				else
				{
					$owned_by .= '<strong>' . $row->authorname . '</strong>';
				}
				$role = $row->role == 1 ? Lang::txt('MOD_MYPROJECTS_STATUS_MANAGER') : Lang::txt('MOD_MYPROJECTS_STATUS_COLLABORATOR');
				$setup = ($row->setup_stage < $setup_complete) ? Lang::txt('MOD_MYPROJECTS_STATUS_SETUP') : '';

				$class = '';
				if ($row->state == 1 && $row->setup_stage >= $setup_complete)
				{
					$class = "pr-active";
				}
				else if ($row->setup_stage < $setup_complete)
				{
					$class = "pr-setup";
				}
				else if ($row->state == 0)
				{
					$class = "pr-inactive";
				}
				$class = $class ? ' class="' . $class . '"' : '';

				$i++;
				?>
					<li <?php echo $class; ?>>
						<a href="<?php echo Route::url('index.php?option=com_projects&task=view&' . $goto); ?>" title="<?php echo $this->escape($row->title) . ' (' . $row->alias . ')'; ?>"><img src="<?php echo Route::url('index.php?option=com_projects&alias=' . $row->alias . '&controller=media&media=thumb'); ?>" alt="<?php echo $this->escape($row->title); ?>" class="project-image" /></a>
						<a href="<?php echo Route::url('index.php?option=com_projects&task=view&' . $goto); ?>" title="<?php echo $this->escape($row->title) . ' (' . $row->alias . ')'; ?>"><?php echo \Hubzero\Utility\String::truncate($this->escape($row->title), 30); ?></a>
						<span class="sub">
							<?php echo $owned_by; ?> | <?php echo $role; ?> <?php
							if ($setup)
							{
								echo ' | ' . $setup;
							}
							else if ($row->state == 0)
							{
								echo ' | ' . Lang::txt('MOD_MYPROJECTS_STATUS_SUSPENDED');
							}
							?>
							<?php if ($row->newactivity && $row->state == 1 && !$setup) { ?>
								<span class="s-new"><?php echo $row->newactivity; ?></span>
							<?php } ?>
						</span>
					</li>
				<?php
			}
			?>
		</ul>
	<?php } else { ?>
		<p><em><?php echo Lang::txt('MOD_MYPROJECTS_NO_PROJECTS'); ?></em></p>
	<?php } ?>

	<?php if ($this->total > $this->limit) { ?>
		<p class="note">
			<?php echo Lang::txt('MOD_MYPROJECTS_YOU_HAVE_MORE', $this->limit, $this->total, Route::url('index.php?option=com_members&id=' . User::get('id') . '&active=projects')); ?>
		</p>
	<?php } ?>
</div>