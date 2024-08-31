<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>


<script src="https://cdn.tailwindcss.com"></script>

<div class="flex flex-col items-center mt-10">
    <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Event Report</h2>

        <?php if($events->isEmpty()): ?>
            <p class="text-center text-gray-500">No events found.</p>
        <?php else: ?>
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Start Date</th>
                        <th class="px-4 py-2 border">End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-4 py-2 border"><?php echo e($event->id); ?></td>
                            <td class="px-4 py-2 border"><?php echo e($event->title); ?></td>
                            <td class="px-4 py-2 border"><?php echo e($event->start_date); ?></td>
                            <td class="px-4 py-2 border"><?php echo e($event->end_date); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/petar/Desktop/SAE_FINAL_BACKEND/sites/event_management/resources/views/home.blade.php ENDPATH**/ ?>